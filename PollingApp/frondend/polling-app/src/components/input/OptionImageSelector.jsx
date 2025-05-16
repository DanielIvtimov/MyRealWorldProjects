import React from 'react'
import "./styles/OptionImageSelector.css"
import { HiOutlineTrash } from 'react-icons/hi';
import { HiMiniPlus } from 'react-icons/hi2';

const OptionImageSelector = ({ imageList, setImageList}) => {

    const handleAddImage = (event) => {
        const file = event.target.files[0];
        if(file && imageList.length < 4 ){
            const reader = new FileReader();
            reader.onload = () => {
                setImageList([
                    ...imageList,
                    { base64: reader.result, file },
                ])
            };
            reader.readAsDataURL(file);
            event.target.value = null;
        };
    };

    const handleDeleteImage = (index) => {
        const updateList = imageList.filter((_, idx) => idx !== index);
        setImageList(updateList);
    };

  return (
    <div>
        {imageList?.length > 0 && (
            <div className='image-selector-grid'>
                {imageList.map((item, index) => (
                    <div key={index} className='image-card'>
                        <img src={item.base64} alt={`Selected_${index}`} className='image-card-img' />  
                        <button onClick={() => handleDeleteImage(index)} className='delete-btn'><HiOutlineTrash className='delete-btn-icon'/></button>
                    </div>
                ))}
            </div>
        )}
        {imageList.length < 4 && (
            <div className='image-upload-container'>
                <input type='file' accept='image/jpeg, image/png,' onChange={handleAddImage} className='image-upload-input' id='imageInput'></input>
                <label htmlFor="imageInput" className='image-upload-label'><HiMiniPlus className='image-upload-icon'/>Select Image</label>
            </div>
        )}
    </div>
  )
}

export default OptionImageSelector