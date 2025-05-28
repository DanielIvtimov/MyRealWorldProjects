import React, { useRef, useState } from "react";
import { LuUser, LuUpload, LuTrash } from "react-icons/lu";
import "./styles/ProfilePhotoSelecot.css";

const ProfilePhotoSelector = ({ image, setImage, preview, setPreview }) => {
  const inputRef = useRef(null);
  const [previewUrl, setPreviewUrl] = useState(null);

  const handleImageChange = (event) => {
    const file = event.target.files[0];
    if (file) {
      setImage(file);
      const preview = URL.createObjectURL(file);
      if (setPreview) {
        setPreview(preview);
      }
      setPreviewUrl(preview);
    }
  };

  const handleRemoveImage = () => {
    setImage(null);
    setPreviewUrl(null);
    if (setPreview) {
      setPreview(null);
    }
    if(inputRef.current){
      inputRef.current.value = null;
    }
  };

  const onChooseFile = () => {
    inputRef.current.click();
  };

  return (
    <div className="profile-photo-container">
      <input
        type="file"
        accept="image/*"
        ref={inputRef}
        onChange={handleImageChange}
        className="hidden-input"
      />
      {!image ? (
        <div className="profile-photo-placeholder">
          <LuUser className="icon-user" />
          <button
            type="button"
            className="upload-button"
            onClick={onChooseFile}
          >
            <LuUpload />
          </button>
        </div>
      ) : (
        <div className="relative-container">
          <img
            src={preview || previewUrl}
            alt="profile photo"
            className="profile-photo-image"
          />
          <button
            type="button"
            className="remove-button"
            onClick={handleRemoveImage}
          >
            <LuTrash />
          </button>
        </div>
      )}
    </div>
  );
};

export default ProfilePhotoSelector;
