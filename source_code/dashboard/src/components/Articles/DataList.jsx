import React, {useEffect, useState} from 'react';
import {FiSearch} from "react-icons/fi";
import {CgRemoveR} from "react-icons/cg";
import axios from "axios";

const DataList = ({onCategoriesChange}) => {

    const [dataList, setDataList] = useState([]);
    const [searchValue, setSearchValue] = useState('');
    const [active, setActive] = useState(false);
    const [selectedItems, setSelectedItems] = useState([]);

    useEffect(() => {
        fetchCategories();
    }, []);

    const fetchCategories = async () => {
        try {
            const response = await axios.get('http://localhost/api/requests/category/get-categories.php');
            const categories = response.data;
            const list = categories.map(cat => ({ ...cat, isChecked: false }));
            setDataList(list);
        } catch (error) {
            console.error('Error fetching categories:', error);
        }
    };

    onCategoriesChange(selectedItems);

    const handleFocus = () => {
        setActive(true);
        displayData(dataList);
    };

    const handleClickOutside = (e) => {
        if (!e.target.classList.contains("dataList_search_input") && !e.target.closest("ul#dataList")) {
            setActive(false);
        }
    };

    const handleCheckboxChange = (event, id) => {
        const updatedDataList = dataList.map(item => item.id === id ? { ...item, isChecked: event.target.checked } : item);
        setDataList(updatedDataList);
        if (event.target.checked) {
            const selectedItem = updatedDataList.find(item => item.id === id);
            setSelectedItems(prevItems => [...prevItems, selectedItem]);
        } else {
            setSelectedItems(prevItems => prevItems.filter(item => item.id !== id));
        }
    };

    const handleRemoveItem = (id) => {
        const updatedDataList = dataList.map(item => item.id === id ? { ...item, isChecked: false } : item);
        setDataList(updatedDataList);
        setSelectedItems(prevItems => prevItems.filter(item => item.id !== id));
    };

    const displayData = () => {
        let arr = dataList.filter(item => item.name.toLowerCase().includes(searchValue.toLowerCase()));
        return arr.map(item => (
            <li key={item.id}>
                <input
                    type="checkbox"
                    id={`data_${item.id}`}
                    checked={item.isChecked}
                    onChange={(e) => handleCheckboxChange(e, item.id)}
                />
                <label htmlFor={`data_${item.id}`}><span>{item.name}</span></label>
            </li>
        ));
    };

    const displaySelectedItems = () => {
        return selectedItems.map(item => (
            <div key={item.id} className="item">
                <p>{item.name}</p>
                <div className="icon-container" onClick={() => handleRemoveItem(item.id)}>
                    <CgRemoveR/>
                </div>
            </div>
        ));
    };

    window.addEventListener("click", handleClickOutside);

    return (
        <div className="collection_card absolut_center">
            <div className="dataList_container">
                <div className={`search_input${active ? " active" : ""}`}>
                    <name htmlFor="dataList_input">
                        <div className="icon-container flex-center">
                            <FiSearch/>
                        </div>
                    </name>
                    <input
                        id="dataList_input"
                        className="dataList_search_input"
                        placeholder="Search for collections"
                        type="text"
                        onFocus={handleFocus}
                        onChange={(e)=>setSearchValue(e.target.value)}
                        value={searchValue}
                    />
                    <ul id="dataList">
                        {displayData()}
                    </ul>
                </div>
                <div className="selected_data">
                    {displaySelectedItems()}
                </div>
            </div>
            <p className="des">Add this article to a category so it’s easy to find in your blog.</p>
        </div>
    );
};

export default DataList;
