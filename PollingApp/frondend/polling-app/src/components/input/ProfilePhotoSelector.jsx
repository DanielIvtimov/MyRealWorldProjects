import React, { useState } from 'react'
import "./styles/ProfilePhotoSelector.css"
import { useRef } from 'react'
import { LuUser, LuUpload, LuTrash } from "react-icons/lu";

const ProfilePhotoSelector = ({image, setImage}) => { 

    const inputRef = useRef(null);
    const [previewUrl, setPreviewUrl] = useState(null);

    const handleImageChange = (event) => {
        const file = event.target.files[0];
        if(file){
            setImage(file);
            const preview = URL.createObjectURL(file);
            setPreviewUrl(preview);
        }
    };

    const handleRemoveImage = () => {
        setImage(null);
        setPreviewUrl(null);
    };

    const onChooseFile = () => {
        inputRef.current.click();
    }

  return (
    <div className='profile-photo-container'>
        <input type="file" accept="image/*" ref={inputRef} onChange={handleImageChange} className='hidden'/>
        {!image ? <div className='profile-photo-wrapper'>
            <LuUser className="profile-photo-icon"/>
            <button type='button' className='profile-upload-button' onClick={onChooseFile}><LuUpload /></button>
        </div> : (
            <div className='relative'>
            <img src={previewUrl} alt="profile photo" className='profile-photo-preview' />
            <button type='button' className='profile-upload-button' onClick={handleRemoveImage} style={{backgroundColor: "red"}}><LuTrash /></button>
        </div>
        )}
    </div>
  )
}

export default ProfilePhotoSelector