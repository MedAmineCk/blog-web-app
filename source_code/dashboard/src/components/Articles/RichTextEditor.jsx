import React, { useState } from 'react';
import ReactQuill from 'react-quill';
import 'react-quill/dist/quill.snow.css';

const RichTextEditor = ({onWriting}) => {
    const [content, setContent] = useState('');

    const handleChange = (newContent) => {
        setContent(newContent);
        onWriting(newContent);
    };

    return (
        <ReactQuill theme="snow" value={content} onChange={handleChange} />
    );
};

export default RichTextEditor;
