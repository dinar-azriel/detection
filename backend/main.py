from fastapi import FastAPI, Query, HTTPException
from fastapi.responses import StreamingResponse
from camera_manager import start_camera, stop_camera, get_last_frame
from models import CameraRegister, CameraInfo, DetectionOut
from detection import capture_detection
from database import conn, cursor
from auth import router as auth_router
import time
import cv2
import os
from typing import List

app = FastAPI()
app.include_router(auth_router)

@app.post("/start_camera")
def api_start_camera(camera_id: int = Query(...)):
    success = start_camera(camera_id)
    if success:
        return {"message": f"Camera {camera_id} started"}
    return {"message": "Camera already running"}

@app.post("/stop_camera")
def api_stop_camera(camera_id: int = Query(...)):
    success = stop_camera(camera_id)
    if success:
        return {"message": f"Camera {camera_id} stopped"}
    return {"message": "Camera not running"}

@app.get("/video_feed")
def video_feed(camera_id: int = Query(...)):
    def generate():
        while True:
            frame = get_last_frame(camera_id)
            if frame is None:
                time.sleep(0.1)
                continue
            _, buffer = cv2.imencode('.jpg', frame)
            yield (b'--frame\r\n'
                   b'Content-Type: image/jpeg\r\n\r\n' + buffer.tobytes() + b'\r\n')
            time.sleep(0.2)
    return StreamingResponse(generate(), media_type='multipart/x-mixed-replace; boundary=frame')

@app.post("/register_camera")
def register_camera(data: CameraRegister):
    cursor.execute(
        "INSERT INTO cameras (camera_id, room_name) VALUES (%s, %s) ON CONFLICT (camera_id) DO UPDATE SET room_name = EXCLUDED.room_name",
        (data.camera_id, data.room_name)
    )
    conn.commit()
    return {"message": "Camera registered", "data": data}

@app.get("/camera_list", response_model=List[CameraInfo])
def camera_list():
    cursor.execute("SELECT camera_id, room_name FROM cameras")
    rows = cursor.fetchall()
    return [{"camera_id": r[0], "room_name": r[1]} for r in rows]

@app.post("/capture", response_model=DetectionOut)
def capture(camera_id: int = Query(...)):
    cursor.execute("SELECT room_name FROM cameras WHERE camera_id = %s", (camera_id,))
    row = cursor.fetchone()
    if not row:
        raise HTTPException(status_code=404, detail="Camera not registered")
    room_name = row[0]
    result = capture_detection(camera_id, room_name)
    if not result:
        raise HTTPException(status_code=404, detail="Frame not available")
    return result
