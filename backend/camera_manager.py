import cv2
import threading
import time
from ultralytics import YOLO
import torch
import os

# Load model dan device
model = YOLO("best.pt")
device = "cuda" if torch.cuda.is_available() else "cpu"

# Struktur penyimpanan global
ACTIVE_CAMERAS = {}
CAMERA_THREADS = {}
LAST_FRAMES = {}

def run_camera(cam_id):
    """
    Menjalankan deteksi real-time dari input: kamera (int) atau video file (str).
    """
    # Gunakan file video jika cam_id berupa string video
    if isinstance(cam_id, str) and cam_id.endswith(('.mp4', '.avi', '.mov', '.mkv')):
        video_path = os.path.join("videos", cam_id)
        cap = cv2.VideoCapture(video_path)
    else:
        cap = cv2.VideoCapture(int(cam_id))

    if not cap.isOpened():
        print(f"[ERROR] Gagal membuka input: {cam_id}")
        return

    while cam_id in ACTIVE_CAMERAS:
        success, frame = cap.read()
        if not success:
            break  # Jika video habis atau kamera gagal, hentikan loop

        # Deteksi objek dengan YOLO
        results = model(frame, device=device, conf=0.4)
        annotated = results[0].plot()

        # Hitung jumlah 'person' (class 0)
        person_count = sum(1 for cls in results[0].boxes.cls if int(cls) == 0)

        # Tambahkan overlay teks
        cv2.putText(
            annotated,
            f"Jumlah Mahasiswa: {person_count}",
            (10, 30),
            cv2.FONT_HERSHEY_SIMPLEX,
            1,
            (0, 255, 0),
            2
        )

        # Simpan frame terakhir
        LAST_FRAMES[cam_id] = annotated
        time.sleep(0.3)

    cap.release()
    print(f"[INFO] Input {cam_id} dihentikan.")

def start_camera(cam_id):
    """
    Memulai input kamera/video berdasarkan ID yang diberikan.
    """
    if cam_id in ACTIVE_CAMERAS:
        return False
    ACTIVE_CAMERAS[cam_id] = True
    t = threading.Thread(target=run_camera, args=(cam_id,))
    t.start()
    CAMERA_THREADS[cam_id] = t
    print(f"[INFO] Memulai input: {cam_id}")
    return True

def stop_camera(cam_id):
    """
    Menghentikan input kamera/video.
    """
    if cam_id in ACTIVE_CAMERAS:
        del ACTIVE_CAMERAS[cam_id]
        print(f"[INFO] Menghentikan input: {cam_id}")
        return True
    return False

def get_last_frame(cam_id):
    """
    Mengambil frame terakhir yang sudah dianotasi.
    """
    return LAST_FRAMES.get(cam_id)
