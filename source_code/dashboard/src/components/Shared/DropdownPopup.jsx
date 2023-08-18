export const DropdownPopup = ({isOpen, children}) => {
    if (!isOpen) return null;
    return (
        <div className="dropdown-popup">
            {children}
        </div>
    )
}