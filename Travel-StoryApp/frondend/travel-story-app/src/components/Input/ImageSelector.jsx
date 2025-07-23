import React, { useEffect, useRef, useState } from 'react'
import "./styles/ImageSelector.css"
import { FaRegFileImage } from 'react-icons/fa';
import { MdDeleteOutline } from 'react-icons/md';

const ImageSelector = ({image, setImage, handleDeleteImg}) => {

    const inputRef = useRef(null);
    const [previewUrl, setPreviewUrl] = useState(null); 

    const handleImageChange = (event) => {
        const file = event.target.files[0];
        if(file){
            setImage(file);
        }
    }

    const onChooseFile = () => {
        inputRef.current.click();
    };

    const handleRemoveImage = () => {
        setImage(null);
        handleDeleteImg();
    }

    useEffect(() => {
        if(typeof image === "string"){
            setPreviewUrl(image);
        } else if (image){
            setPreviewUrl(URL.createObjectURL(image));
        } else {
            setPreviewUrl(null);
        }

        return () => {
            if(previewUrl && typeof previewUrl === "string" && !image){
                URL.revokeObjectURL(previewUrl);
            } 
        }
    }, [image])

  return (
    <div>
        <input type="file" accept="image/*" ref={inputRef} onChange={handleImageChange} className="image-file-input" />
        {!image ? (<button className="image-upload-trigger" onClick={() => onChooseFile()}>
            <div className="image-upload-icon-wrapper">
                <FaRegFileImage className="image-upload-icon" />
            </div>
            <p className="image-upload-description">Browse image files to upload</p>  
        </button>) : 
            (<div className="image-preview-container">
                <img src={previewUrl} alt="Selected" className="image-preview" />
                <button className="image-remove-button" onClick={handleRemoveImage}>
                    <MdDeleteOutline className="image-remove-icon" />
                </button>
            </div>)
        }
    </div>
  )
}

export default ImageSelector