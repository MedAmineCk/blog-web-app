import {faker} from "@faker-js/faker";

const notificationsDataTypes = [
    "Some one Like the Article",
    "Some one Share the Article",
    "Some one click on the Ad",
    "Some one bookmark the Article"
]
const notificationsData = [];
for (let i = 0; i < 5; i++) {
    const date = faker.date.recent().toUTCString().substring(0, 17);

    let notificationObj = {
        id: faker.string.uuid().substring(0, 5),
        title: faker.helpers.arrayElement(notificationsDataTypes),
        date: faker.date.recent().toUTCString().substring(0, 17)
    }

    notificationsData.push(notificationObj)
}


export default notificationsData;