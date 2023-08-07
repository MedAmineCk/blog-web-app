import {faker} from "@faker-js/faker";

const commentsData = [];
for (let i = 0; i < 5; i++) {

    let notificationObj = {
        id: faker.string.uuid().substring(0, 5),
        title: faker.lorem.lines({min: 1, max: 2}),
        date: faker.date.recent().toUTCString().substring(0, 17)
    }

    commentsData.push(notificationObj)
}


export default commentsData;