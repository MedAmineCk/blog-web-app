import React, { useState } from 'react';
import {CgRemoveR} from "react-icons/cg";
import {CiCircleRemove} from "react-icons/ci";

const TagInput = ({onTagsChange}) => {
    const [tags, setTags] = useState([]);
    const [inputValue, setInputValue] = useState('');

    onTagsChange(tags);

    const handleInputChange = (e) => {
        setInputValue(e.target.value);
    };

    const handleInputKeyPress = (e) => {
        if (e.key === 'Enter' && inputValue.trim() !== '') {
            setTags([...tags, inputValue.trim()]);
            setInputValue('');
        }
    };

    const handleTagRemove = (tagToRemove) => {
        const updatedTags = tags.filter(tag => tag !== tagToRemove);
        setTags(updatedTags);
    };

    return (
        <div className="tag-input-container">
            <div className="tags">
                {tags.map((tag, index) => (
                    <div key={index} className="tag">
                        {tag}
                        <span className="tag-remove" onClick={() => handleTagRemove(tag)}>
                          <CiCircleRemove/>
                        </span>
                    </div>
                ))}
            </div>
            <input
                type="text"
                value={inputValue}
                onChange={handleInputChange}
                onKeyPress={handleInputKeyPress}
                placeholder="Type and press Enter to add a tag"
            />
        </div>
    );
};

export default TagInput;
