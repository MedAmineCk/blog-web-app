import {faker} from "@faker-js/faker";

export const DataItem = ({label}) => {
    return (
        <div className="data-item flex-container flex-center">
            <span>{label}</span>
            <span>{faker.number.int({min: 10, max: 999})}</span>
        </div>
    )
}