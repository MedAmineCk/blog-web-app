export const DropdownPopup = ({isOpen, children, onDelete}) => {
    if (!isOpen) return null;
    return (
        <div className="dropdown-popup">
            {children}
        </div>
    )
}