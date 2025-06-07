import cv2
import os
from datetime import datetime
from ultralytics import YOLO
from database import cursor, conn
from models import DetectionOut
from camera_manager import get_last_frame
import torch
from dotenv import load_dotenv


load_dotenv()

CAPTURE_DIR = "captures"
os.makedirs(CAPTURE_DIR, exist_ok=True)

# Load model dari .env jika tersedia
model_path = os.getenv("YOLO_MODEL_PATH", "best.pt")
env_device = os.getenv("YOLO_DEVICE", "cuda")
device = env_device if env_device == "cpu" or torch.cuda.is_available() else "cpu"

model = YOLO(model_path)

def capture_detection(camera_id: int, room_name: str) -> DetectionOut:
    frame = get_last_frame(camera_id)
    if frame is None:
        return None

    timestamp = datetime.now()
    filename = f"{room_name.replace(' ', '_')}_{timestamp.strftime('%Y%m%d_%H%M%S')}.jpg"
    image_path = f"{CAPTURE_DIR}/{filename}"
    cv2.imwrite(image_path, frame)

    person_count = count_people(frame)

    cursor.execute(
        "INSERT INTO detections (camera_id, room_name, timestamp, person_count, image_path) VALUES (%s, %s, %s, %s, %s)",
        (camera_id, room_name, timestamp, person_count, image_path)
    )
    conn.commit()

    return DetectionOut(
        id=None,
        camera_id=camera_id,
        room_name=room_name,
        timestamp=timestamp,
        person_count=person_count,
        image_path=image_path
    )

def count_people(frame) -> int:
    results = model(frame, device=device)
    return sum(1 for cls in results[0].boxes.cls if int(cls) == 0)
