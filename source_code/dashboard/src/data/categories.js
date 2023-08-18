import axios from 'axios';

// Replace with your actual API endpoint
const API_URL = 'http://localhost/api/requests/category/get-categories.php';

// Function to fetch categories
const fetchCategories = async () => {
    try {
        const response = await axios.get(API_URL);
        return response.data; // Array of categories
    } catch (error) {
        console.error('Error fetching categories:', error);
        return [];
    }
};

// Call the function to fetch categories
fetchCategories()
    .then(categories => {
        // Use the categories in your application
        console.log('Fetched categories:', categories);
    });

const categories = [
    {
        id: 1,
        label: 'Home'
    },
    {
        id: 2,
        label: 'Articles'
    },
    {
        id: 3,
        label: 'Reviews'
    },
    {
        id: 4,
        label: 'Tips'
    },
    {
        id: 5,
        label: 'Collections'
    }
]

export default categories;