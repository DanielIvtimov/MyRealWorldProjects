import cloudinary from "./cloudinary.js"

async function uploadFileToCloudinary(file){
    return new Promise((resolve, reject) => {
        const uploadStream = cloudinary.uploader.upload_stream(
            {
                resource_type: "raw",
                public_id: `resumes/${file.originalname.split('. ')[0]}`,
                access_mode: "public",
            },
            (error, result) => {
                if(error){
                    reject(error);
                } else {
                    resolve(result);
                }
            }
        );
        uploadStream.end(file.buffer);
    });
}

export default uploadFileToCloudinary;