import cv2
import os
from datetime import datetime
from ultralytics import YOLO
import torch
from database import conn, cursor
from camera_manager import get_last_frame
from models import DetectionOut

model = YOLO("best.pt")
device = "cuda" if torch.cuda.is_available() else "cpu"
CAPTURE_DIR = "captures"
os.makedirs(CAPTURE_DIR, exist_ok=True)

def count_people(frame):
    results = model(frame, device=device)
    return sum(1 for cls in results[0].boxes.cls if int(cls) == 0)

def capture_detection(camera_id: int, room_name: str) -> DetectionOut | None:
    frame = get_last_frame(camera_id)
    if frame is None:
        return None
    timestamp_str = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    ts_filename = datetime.now().strftime("%Y%m%d_%H%M%S")
    filename = f"{room_name}_{ts_filename}.jpg"
    filepath = os.path.join(CAPTURE_DIR, filename)
    cv2.imwrite(filepath, frame)
    person_count = count_people(frame)
    cursor.execute(
        "INSERT INTO detections (camera_id, room_name, timestamp, person_count, image_path) VALUES (%s, %s, %s, %s, %s)",
        (camera_id, room_name, timestamp_str, person_count, filepath)
    )
    conn.commit()
    return DetectionOut(
        camera_id=camera_id,
        room_name=room_name,
        timestamp=datetime.strptime(timestamp_str, "%Y-%m-%d %H:%M:%S"),
        person_count=person_count,
        image_path=filepath
    )
