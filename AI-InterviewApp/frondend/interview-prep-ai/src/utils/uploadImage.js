import { API_PATHS } from "./apiPaths";
import axiosInstace from "./axiosInstace";

const uploadImage = async (imageFile) => {
    const formData = new FormData();
    formData.append('image', imageFile);
    try{
       const response = await axiosInstace.post(API_PATHS.IMAGE.UPLOAD_IMAGE, formData, {
        headers: {
            "Content-Type": "multipart/form-data",
        },
       });
       return response.data;
    }catch(error){
     console.error("Error uploading the image:", error);
     throw error;   
    }
};

export default uploadImage;