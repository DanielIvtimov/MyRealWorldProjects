const express = require("express");
const { registerUser, loginUser, getUserInfo } = require("../controllers/authController");
const { protect } = require("../middleware/authMiddleware");
const upload = require("../middleware/uploadMiddleware");

const router = express.Router();

router.post("/register", registerUser);
router.post("/login", loginUser);
router.get("/getUser", protect, getUserInfo); 

router.post("/upload-image", upload.single("image"), (request, response) => {
    if(!request.file){
        return response.status(400).json({message: "No file uploaded"});
    }
    const imageUrl = `${request.protocol}://${request.get("host")}/uploads/${request.file.filename}`;
    response.status(200).json({ imageUrl });
});

module.exports = router;