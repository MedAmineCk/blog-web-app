import React, { useState } from 'react';
import axios from 'axios';
import { BsCardImage } from 'react-icons/bs';

const ThumbnailUpload = ({ onUpload }) => {
    const [selectedFile, setSelectedFile] = useState(null);
    const [uploading, setUploading] = useState(false);
    const [previewUrl, setPreviewUrl] = useState(null);

    const handleUpload = async (e) => {
        const file = e.target.files[0];
        setSelectedFile(file);
        setPreviewUrl(URL.createObjectURL(file)); // Create a preview URL for the selected file
        if (!file) return; // Use 'file' instead of 'selectedFile'

        setUploading(true);

        try {
            const formData = new FormData();
            formData.append('thumbnail', file); // Use 'file' instead of 'selectedFile'

            const response = await axios.post('http://localhost/api/requests/article/upload-thumbnail.php', formData);
            const thumbnailUrl = response.data.thumbnailUrl;
            console.log({thumbnailUrl})

            onUpload(thumbnailUrl); // Pass the thumbnail URL to the parent component
        } catch (error) {
            console.error('Thumbnail upload error:', error);
        }

        setUploading(false);
    };


    return (
        <div>
            <input id="thumbnail" type="file" onChange={handleUpload} accept="image/*" style={{ display: 'none' }} />
            <label htmlFor="thumbnail" className="thumbnail-container flex-center">
                {uploading ? (<div className="uploading"></div> ):''}
                {previewUrl ? (
                    <img src={previewUrl} alt="Preview" className="thumbnail-preview" />
                ) : (
                    <div className="icon-container">
                        <BsCardImage />
                    </div>
                )}
            </label>
            <div className="buttons flex-container">
                <button onClick={()=>setPreviewUrl(null)}>cancel</button>
            </div>
        </div>
    );
};

export default ThumbnailUpload;
