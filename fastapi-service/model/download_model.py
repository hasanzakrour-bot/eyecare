from huggingface_hub import snapshot_download

snapshot_download(
    repo_id="eliasteikari/retinal_disease_model",
    local_dir="model/retinal_disease_model",
    local_dir_use_symlinks=False
)

print("Model downloaded successfully.")
