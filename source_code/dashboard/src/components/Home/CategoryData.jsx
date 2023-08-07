export const CategoryData = ({label, data, percentage}) => {
    return (
        <div className="category-item">
            <div className="category-data flex-container">
                <span>{label}</span>
                <span>{data}</span>
            </div>
            <div className="bar">
                <div className="percentage" style={{width: `${percentage}%`}}></div>
            </div>
        </div>
    )
}