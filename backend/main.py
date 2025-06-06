from fastapi import FastAPI, Query, HTTPException
from fastapi.responses import StreamingResponse
from fastapi.middleware.cors import CORSMiddleware
from typing import List
import time
import cv2
import os

from camera_manager import start_camera, stop_camera, get_last_frame
from models import CameraRegister, CameraInfo, DetectionOut
from detection import capture_detection, count_people
from database import conn, cursor
from auth import router as auth_router

app = FastAPI()
app.include_router(auth_router)

# Untuk izinkan akses dari frontend
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # Ganti ke domain frontend jika di production
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Folder simpan gambar hasil capture
CAPTURE_DIR = "captures"
os.makedirs(CAPTURE_DIR, exist_ok=True)

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

@app.get("/detection_list", response_model=List[DetectionOut])
def detection_list():
    cursor.execute(
        "SELECT id, camera_id, room_name, timestamp, person_count, image_path FROM detections ORDER BY timestamp DESC"
    )
    rows = cursor.fetchall()
    return [
        DetectionOut(
            id=r[0],
            camera_id=r[1],
            room_name=r[2],
            timestamp=r[3],
            person_count=r[4],
            image_path=r[5]
        ) for r in rows
    ]

@app.delete("/detection/{id}")
def delete_detection(id: int):
    cursor.execute("SELECT id FROM detections WHERE id = %s", (id,))
    if not cursor.fetchone():
        raise HTTPException(status_code=404, detail="Detection not found")
    cursor.execute("DELETE FROM detections WHERE id = %s", (id,))
    conn.commit()
    return {"message": f"Detection ID {id} deleted"}
