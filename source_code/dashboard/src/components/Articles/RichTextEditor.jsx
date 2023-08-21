import React, {useEffect, useState} from 'react';
import ReactQuill from 'react-quill';
import 'react-quill/dist/quill.snow.css';

const RichTextEditor = ({onWriting, article_content}) => {
    const [content, setContent] = useState('');

    const handleChange = (newContent) => {
        setContent(newContent);
        onWriting(newContent);
    };

    useEffect(() => {
        setContent(article_content || '')
    }, [article_content]);


    return (
        <ReactQuill theme="snow" value={content} onChange={handleChange}/>
    );
};

export default RichTextEditor;
