import {faker} from "@faker-js/faker";

const adsArr = [];
for (let i = 0; i < 10; i++) {
    let adsObj = {
        id: faker.string.uuid().substring(0, 5),
        type: faker.helpers.arrayElement(["popup", "similar", "offer", "inline"]),
        reach: faker.number.int({min: 100, max: 999}),
        clicks: faker.number.int({min: 10, max: 100})
    }
    adsArr.push(adsObj)
}

export default adsArr;