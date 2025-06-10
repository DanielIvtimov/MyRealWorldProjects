import multer from "multer";

const storage = multer.diskStorage({
    destination: (request, file, cb) => {
        cb(null, "uploads/");
    },
    filename: (request, file, cb) => {
        cb(null, `${Date.now()}-${file.originalname}`);
    },
});

const fileFilter = (request, file, cb) => {
    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    if(allowedTypes.includes(file.mimetype)){
        cb(null, true);
    } else {
        cb(new Error('Only .jpeg, .jpg and .png formats are allowed'), false);
    }
};

const upload = multer({storage, fileFilter});

export default upload;