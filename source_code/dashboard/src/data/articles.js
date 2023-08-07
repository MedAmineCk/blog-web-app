import {faker} from "@faker-js/faker";

const articlesArr = [];
for (let i = 0; i < 3; i++) {
    const thumbnail = faker.image.urlPicsumPhotos();
    const title = faker.lorem.lines(1);
    const subtitle = faker.lorem.lines(1);
    const date = faker.date.soon({refDate: '2023-01-01'}).toUTCString().substring(17, 2);
    const category = faker.helpers.arrayElement(["fiction", "non fiction", "science"]);
    const type = faker.helpers.arrayElement(["Article", "Review"]);
    const reads = faker.number.int({min: 0, max: 999});
    const comments = faker.number.int({min: 0, max: 20});
    const favorites = faker.number.int({min: 0, max: 20});
    let articleObj = {
        thumbnail: thumbnail,
        title: title,
        subtitle: subtitle,
        date: date,
        category: category,
        type: type
    }
    let dataObj = {
        reads: reads,
        comments: comments,
        favorites: favorites
    }

    let itemObj = {articleObj, dataObj}

    articlesArr.push(itemObj)
}

export default articlesArr;