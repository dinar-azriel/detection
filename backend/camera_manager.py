import cv2
import threading
import time
from ultralytics import YOLO
import torch

model = YOLO("best.pt")
device = "cuda" if torch.cuda.is_available() else "cpu"

ACTIVE_CAMERAS = {}
CAMERA_THREADS = {}
LAST_FRAMES = {}

def run_camera(cam_id: int):
    cap = cv2.VideoCapture(cam_id)
    if not cap.isOpened():
        return
    while cam_id in ACTIVE_CAMERAS:
        success, frame = cap.read()
        if not success:
            continue
        results = model(frame, device=device)
        annotated = results[0].plot()
        LAST_FRAMES[cam_id] = annotated
        time.sleep(0.3)
    cap.release()

def start_camera(cam_id: int):
    if cam_id in ACTIVE_CAMERAS:
        return False
    ACTIVE_CAMERAS[cam_id] = True
    t = threading.Thread(target=run_camera, args=(cam_id,))
    t.start()
    CAMERA_THREADS[cam_id] = t
    return True

def stop_camera(cam_id: int):
    if cam_id in ACTIVE_CAMERAS:
        del ACTIVE_CAMERAS[cam_id]
        return True
    return False

def get_last_frame(cam_id: int):
    return LAST_FRAMES.get(cam_id)
