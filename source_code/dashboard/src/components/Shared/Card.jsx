export const Card = ({className, label, children}) => {
    return (
        <div className={`card ${className}`}>
            <div className="label">{label}</div>
            <div className="card-container">
                {children}
            </div>
        </div>
    )
}