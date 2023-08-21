import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { BsCardImage } from 'react-icons/bs';
import { MdOutlineImageNotSupported } from 'react-icons/md';

const ThumbnailUpload = ({ existingThumbnailUrl, onUpload }) => {
    const [selectedFile, setSelectedFile] = useState(null);
    const [uploading, setUploading] = useState(false);
    const thumbnailURL = (existingThumbnailUrl) ? `http://localhost/api/uploads/${existingThumbnailUrl}` : null;
    const [previewUrl, setPreviewUrl] = useState(thumbnailURL);

    const handleUpload = async (e) => {
        const file = e.target.files[0];
        setSelectedFile(file);
        setPreviewUrl(URL.createObjectURL(file));
        if (!file) return;

        setUploading(true);

        try {
            const formData = new FormData();
            formData.append('thumbnail', file);

            const response = await axios.post('http://localhost/api/requests/article/upload-thumbnail.php', formData);
            const thumbnailUrl = response.data.thumbnailUrl;

            onUpload(thumbnailUrl);
        } catch (error) {
            console.error('Thumbnail upload error:', error);
        }

        setUploading(false);
    };

    return (
        <div>
            <input id="thumbnail" type="file" onChange={handleUpload} accept="image/*" style={{ display: 'none' }} />
            <label htmlFor="thumbnail" className="thumbnail-container flex-center">
                {uploading ? <div className="uploading"></div> : ''}
                {previewUrl ? (
                    <img src={previewUrl} alt="Preview" className="thumbnail-preview" />
                ) : (
                    <div className="icon-container">
                        <BsCardImage />
                    </div>
                )}
            </label>
            {previewUrl ? (
                <div className="remove-img" onClick={() => setPreviewUrl(null)}>
                    <MdOutlineImageNotSupported />
                </div>
            ) : null}
        </div>
    );
};

export default ThumbnailUpload;
