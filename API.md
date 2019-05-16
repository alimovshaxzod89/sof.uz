#API Integration v1.2

**API endpoint (development):** `http://api.magento.uz/v1/`
**API endpoint (production):** `http://api.xabar.uz/v1/`

**API method:** JSON REST

##Required query parameters for each request

These parameters intended for authorization purpose and required to provide on PRODUCTION mode of the API:

- *l* - language parameter, valid values: uz-UZ, cy-UZ, ru-RU
- *t* - random string, for example timestamp. Generate unique for each request
- *h* - hash of the parameter *t* calculated by given method below:


    t = generateRandomStringLikeTimeStamp();
    h = md5(t + "#_#" + t);

##Response format

API sends JSON with HTTP code 200 to back for each request, original HTTP code of the response wrapped into *status* field
 of the response. If response code differs from 200 than request/response is considered as communication or server failure. 
 Every response contains following fields:

- *success* - true if request/response success 
- *status* - original HTTP code of the response (200 if success)
- *message* - appropriate message if *success* is false
- *OTHER FIELDS* - for individual methods

**NOTE:** Some attributes of the objects are translatable, it includes three language at same time and values imploded 
by the separator `#|#`, e.g: `Jamiyat#|#Жамият#|#Общество`

##API methods

###1.List of categories

Request: `GET: home/categories`

Response: 

    {
        "items": [
            {
                "id": "5a004eb12f19e0be6f20710c",
                "name": "O'zbekiston#|#Ўзбекистон#|#Узбекистан",
                "slug": "ozbekiston",
                "created_at": 1509969585,
                "child": []
            },
            {
                "id": "5a004ecb2f19e0487720710c",
                "name": "Xorij#|#Хориж#|#Зарубеж",
                "slug": "xorij",
                "created_at": 1509969611,
                "child": []
            },
            {
                "id": "5a09b5a62f19e05a6be771f7",
                "name": "Jamiyat#|#Жамият#|#Общество",
                "slug": "jamiyat",
                "created_at": 1510585766,
                "child": []
            },
            {
                "id": "5a004eda2f19e07f7020710b",
                "name": "Sport#|#Спорт#|#Sport",
                "slug": "sport",
                "created_at": 1509969626,
                "child": [
                    {
                        "id": "5a2baf931d6c65cc047b23c6",
                        "name": "Fudbol#|#Fudbol#|#Футбол",
                        "slug": "fudbol",
                        "created_at": 1512812435,
                        "child": []
                    }
                ]
            }
        ],
        "success": true,
        "status": 200
    }

**NOTE:** Response contains category list as tree view, max depth of the tree is 2

###2.List of tags

Request: `GET: home/tags`

Response: 

    {
        "items": [
            {
                "id": "5a0d7a922f19e0ba5e840976",
                "name": "O'zbekiston#|#Ўзбекистон#|#Узбекистан",
                "slug": "uzbekiston",
                "count": 0
            },
            {
                "id": "5a0d7b012f19e0ba5e840977",
                "name": "Benzin#|#Бензин#|#Бензин",
                "slug": "benzin",
                "count": 0
            },
            {
                "id": "5a0d7c132f19e0145f840979",
                "name": "Narx#|#Нарх#|#Цена",
                "slug": "narx",
                "count": 0
            }
        ],
        "success": true,
        "status": 200
    }



###3.All assets: trending tags, categories, web view css, web view js

Request: `GET: home/assets`

Response: 

    {
        "css": "body {\n    font-size: 14px;\n}",
        "js": "",
        "tags": [
            {
                "id": "5a0d7a922f19e0ba5e840976",
                "name": "O'zbekiston#|#Ўзбекистон#|#Узбекистан",
                "slug": "uzbekiston",
                "count": 571
            },
            {
                "id": "5a34ddd201abe2886d3382de",
                "name": "Shavkat Mirziyoyev#|#Шавкат Мирзиёев#|#Шавкат Мирзиёев",
                "slug": "mening-prezidentim",
                "count": 299
            },
            {
                "id": "5a32647301abe2ca61843926",
                "name": "futbol#|#футбол#|#футбол",
                "slug": "futbol",
                "count": 201
            }
        ],
        "categories": [
            {
                "id": "5a2bf56e01abe2b432da115d",
                "name": "Siyosat#|#Сиёсат#|#Сиёсат",
                "slug": "siyosat",
                "created_at": 1512830318,
                "child": [
                ]
            },
            {
                "id": "5a2bcdedd9858760228b456c",
                "name": "Iqtisodiyot#|#Иқтисодиёт#|#Иқтисодиёт",
                "slug": "iqtisodiyot",
                "created_at": 1512820205,
                "child": [
                ]
            },
            {
                "id": "5a2bf5db01abe2b532da115e",
                "name": "Tahlil#|#Таҳлил#|#Таҳлил",
                "slug": "tahlil",
                "created_at": 1512830427,
                "child": [
                ]
            }
        ],
        "success": true,
        "status": 200
    }


###4.Storing Google Cloud Messaging client id to the API

Request: `GET: api/gcm-token?token=406db82a5:17b5df406db82a517b5df450adc2900d5d6f58450adc2900d5d6f58`

Response: 
    
    {
      "token": "406db82a5:17b5df406db82a517b5df450adc2900d5d6f58450adc2900d5d6f58",
      "updated_at": 1491023554,
      "success": true,
      "status": 200
    }


###5.Get last posts

Request: `GET post/list?page=2&type=all&category=5a09b5a62f19e05a6be771f7&tag=5a0d7a922f19e0ba5e840976`

Result will be sorted by `published_on` field and divided into several `page`s with limited number of posts. All of the query params is optional with following default values:

- `page` Page number, default value is `0`
- `type` Type of the posts, default value is `all`. Available values `[all,gallery,audio,video]`
- `category` Posts filtered by category_id, default value is null
- `tag` Posts filtered by tag_id, default value is null

Response: 

    {
        "items": [
            {
                "id": "5a82a90b8203751b309fec30",
                "short_id": "n18",
                "title": "Марказий банк раҳбариятида ўзгариш бўлди",
                "info": "Алишер Акмалов 1962 йил Тошкент вилоятида туғилган. Маълумоти — олий, 1983 йилда Тошкент халқ хўжалиги институтини тамомлаган. Мутахассислиги — ишлаб чиқаришни режалаштириш.",
                "views": 129,
                "comment_count": 0,
                "label": "important",
                "image": "http://static.xabar.lc/crop/m/2/1/medium_normal_2100121836.jpg",
                "image_caption": "",
                "image_source": "Фото: «Gazeta.uz»",
                "categories": "5a2bcdedd9858760228b456c",
                "view_url": "https://www.xabar.lc/post/markaziy-bank-rahbariyatida-ozgarish-boldi",
                "audio_url": false,
                "video_url": false,
                "youtube_url": null,
                "published_on": 1518512640,
                "updated_at": 1518512792,
                "has_audio": false,
                "has_video": false,
                "has_gallery": false,
                "has_russian": false,
                "has_priority": true
            },
            {
                "id": "5a82a36a8203756e219fec31",
                "short_id": "7mf",
                "title": "Британияликларнинг Ўзбекистонга келиши сезиларли равишда ошади",
                "info": "Бу эса икки мамлакат ўртасидаги сайёҳлик алоқаларини мустаҳкамлаш ва Ўзбекистоннинг сайёҳлик салоҳиятини Британия сайёҳлик хизматлари бозорида оммалаштиришда муҳим қадамдир, дейилади хабарда.",
                "views": 75,
                "comment_count": 0,
                "label": "regular",
                "image": "http://static.xabar.uz/crop/m/2/8/medium_normal_2888870050.jpg",
                "image_caption": "",
                "image_source": "Фото: «Ўзбекистон ҳаво йўллари» МАК",
                "categories": "5a09b5a62f19e05a6be771f7",
                "view_url": "https://www.xabar.uz/post/britaniyaliklarning-ozbekistonga-kelishi-sezilarli-oshadi",
                "audio_url": false,
                "video_url": false,
                "youtube_url": null,
                "published_on": 1518511131,
                "updated_at": 1518511150,
                "has_audio": false,
                "has_video": false,
                "has_gallery": false,
                "has_russian": false,
                "has_priority": false
            }
        ],
        "page": 0,
        "type": "all",
        "limit": 2,
        "category": null,
        "tag": null,
        "success": true,
        "status": 200
    }



###6.Get top posts

Request: `GET post/top?page=2&order=views_today`

Result will be sorted by `order` param field and divided into several `page`s with limited number of posts. 
`order` param values:
- `views_today` Today's top posts
- `views_l3d` Top posts in last 3 days
- `views_l7d` Top posts in last 7 days
- `views_l30d` Top posts in last 30 days
- `views` Overall top posts

Response: 

    {
        "items": [
            {
                "id": "5a82a90b8203751b309fec30",
                "short_id": "n18",
                "title": "Марказий банк раҳбариятида ўзгариш бўлди",
                "info": "Алишер Акмалов 1962 йил Тошкент вилоятида туғилган. Маълумоти — олий, 1983 йилда Тошкент халқ хўжалиги институтини тамомлаган. Мутахассислиги — ишлаб чиқаришни режалаштириш.",
                "views": 129,
                "comment_count": 0,
                "label": "important",
                "image": "http://static.xabar.lc/crop/m/2/1/medium_normal_2100121836.jpg",
                "image_caption": "",
                "image_source": "Фото: «Gazeta.uz»",
                "categories": "5a2bcdedd9858760228b456c",
                "view_url": "https://www.xabar.lc/post/markaziy-bank-rahbariyatida-ozgarish-boldi",
                "audio_url": false,
                "video_url": false,
                "youtube_url": null,
                "published_on": 1518512640,
                "updated_at": 1518512792,
                "has_audio": false,
                "has_video": false,
                "has_gallery": false,
                "has_russian": false,
                "has_priority": true
            },
            {
                "id": "5a82a36a8203756e219fec31",
                "short_id": "7mf",
                "title": "Британияликларнинг Ўзбекистонга келиши сезиларли равишда ошади",
                "info": "Бу эса икки мамлакат ўртасидаги сайёҳлик алоқаларини мустаҳкамлаш ва Ўзбекистоннинг сайёҳлик салоҳиятини Британия сайёҳлик хизматлари бозорида оммалаштиришда муҳим қадамдир, дейилади хабарда.",
                "views": 75,
                "comment_count": 0,
                "label": "regular",
                "image": "http://static.xabar.uz/crop/m/2/8/medium_normal_2888870050.jpg",
                "image_caption": "",
                "image_source": "Фото: «Ўзбекистон ҳаво йўллари» МАК",
                "categories": "5a09b5a62f19e05a6be771f7",
                "view_url": "https://www.xabar.uz/post/britaniyaliklarning-ozbekistonga-kelishi-sezilarli-oshadi",
                "audio_url": false,
                "video_url": false,
                "youtube_url": null,
                "published_on": 1518511131,
                "updated_at": 1518511150,
                "has_audio": false,
                "has_video": false,
                "has_gallery": false,
                "has_russian": false,
                "has_priority": false
            }
        ],
        "page": 0,
        "type": "all",
        "limit": 2,
        "category": null,
        "tag": null,
        "success": true,
        "status": 200
    }



###6.Get post data

Request: `GET: post/view?id=5a82a36a8203756e219fec31`

Response: 
    
    {
        "post": {
            "id": "5a82a36a8203756e219fec31",
            "short_id": "7mf",
            "title": "Британияликларнинг Ўзбекистонга келиши сезиларли равишда ошади",
            "info": "Бу эса икки мамлакат ўртасидаги сайёҳлик алоқаларини мустаҳкамлаш ва Ўзбекистоннинг сайёҳлик салоҳиятини Британия сайёҳлик хизматлари бозорида оммалаштиришда муҳим қадамдир, дейилади хабарда.",
            "views": 75,
            "comment_count": 0,
            "label": "regular",
            "image": "http://static.xabar.lc/crop/m/2/8/medium_normal_2888870050.jpg",
            "image_caption": "",
            "image_source": "Фото: «Ўзбекистон ҳаво йўллари» МАК",
            "categories": "5a09b5a62f19e05a6be771f7",
            "view_url": "https://www.xabar.lc/post/britaniyaliklarning-ozbekistonga-kelishi-sezilarli-oshadi",
            "audio_url": false,
            "video_url": false,
            "youtube_url": null,
            "published_on": 1518511131,
            "updated_at": 1518511150,
            "has_audio": false,
            "has_video": false,
            "has_gallery": false,
            "has_russian": false,
            "has_priority": false,
            "content": "<p style=\"text-align: justify;\">&laquo;Ўзбекистон ҳаво йўллари&raquo; миллий авиакомпанияси Буюк Британия Мустақил сайёҳлик операторлари ассоциацияси (AITO) аъзоси бўлди. Бу ҳақда МАК ахборот хизмати <a href=\"https://www.uzairways.com/uz/news/ozbekiston-havo-yollari-milliy-aviakompaniyasi-buyuk-britaniya-mustaqil-sayyohlik-operatorlari\" target=\"_blank\" rel=\"noopener\">хабар берди</a>.</p>\r\n<p style=\"text-align: justify;\">Бу эса икки мамлакат ўртасидаги сайёҳлик алоқаларини мустаҳкамлаш ва Ўзбекистоннинг сайёҳлик салоҳиятини Британия сайёҳлик хизматлари бозорида оммалаштиришда муҳим қадамдир, дейилади хабарда.</p>\r\n<p style=\"text-align: justify;\">Ушбу уюшма аъзолиги &laquo;Ўзбекистон ҳаво йўллари&raquo; бренди ва мамлакатнинг сайёҳлик маҳсулотларини кенг тарғиб қилиш, шунингдек, Буюк Британиядан Ўзбекистонга сайёҳлар оқимини сезиларли даражада ошириш имконини беради.</p>\r\n<figure class=\"image\"><img src=\"https://static.xabar.uz/uploads/1/720__fl6GB9ykAqd4LykaNoe3Q7I9kNSp4s2B.jpg\" alt=\"\" />\r\n<figcaption><em><strong>AITO аъзолик сертификати</strong></em><br /><strong>Фото: &laquo;Ўзбекистон ҳаво йўллари&raquo; МАК</strong></figcaption>\r\n</figure>\r\n<p style=\"text-align: justify;\">Маълумот учун, Буюк Британия Мустақил сайёҳлик операторлари ассоциацияси (AITO) ўз сафига 120га яқин ихтисослашган ва мустақил туроператорларни бирлаштирган Буюк Британиянинг туризм бўйича савдо гуруҳи ҳисобланиб, унинг аъзолари ноёб маданиятга, бой тарихга ва алоҳида табиий шароитларга эга давлатларнинг туристик маҳсулотини тақдим этиш борасида 170 дан ортиқ мамлакатда фаолият юритади. AITO 1976 йилда ташкил топган бўлиб, унинг штаб-квартираси Лондоннинг жануби-ғарбидаги Твиcкенҳам шаҳрида жойлашган.</p>",
            "tags": [
                {
                    "v": "5a3b4f4e01abe25208f1d1de",
                    "t": "Буюк Британия"
                },
                {
                    "v": "5a51f23601abe2dd6a5fbae6",
                    "t": "«Ўзбекистон ҳаво йўллари»"
                },
                {
                    "v": "5a82a3bf820375be209fec35",
                    "t": "AITO"
                }
            ],
            "similar": [
                {
                    "id": "5a7d849a820375d9172c09ec",
                    "short_id": "9ul",
                    "title": "«Ўзбекистон ҳаво йўллари» МАК матбуот хизмати нега ахборот беришдан қўрқади?",
                    "info": "«Ўзбекистон ҳаво йўллари» МАК тизимида қандай ишлар амалга ошириляпти? Авиакомпания яқин орада чипталар арзонлашуви билан боғлиқ масалани режага киритганми? Шу каби саволларга жавоб топиш мақсадида компаниянинг матбуот хизматига қўнғироқ қилдик.",
                    "views": 1103,
                    "comment_count": 1,
                    "label": "important",
                    "image": "http://static.xabar.lc/crop/m/2/5/medium_normal_2506177531.jpg",
                    "image_caption": "",
                    "image_source": "Фото: «Uzairways.com»",
                    "categories": "5a09b5a62f19e05a6be771f7",
                    "view_url": "https://www.xabar.lc/post/mak-matbuot-xizmati-nega-axborot-berishdan-qorqadi",
                    "audio_url": false,
                    "video_url": false,
                    "youtube_url": null,
                    "published_on": 1518178500,
                    "updated_at": 1518179731,
                    "has_audio": false,
                    "has_video": false,
                    "has_gallery": false,
                    "has_russian": false,
                    "has_priority": true
                },
                {
                    "id": "5a7aa342335bb1635c48614a",
                    "short_id": "avo",
                    "title": "«Ўзбекистон ҳаво йўллари» Тайландда тақдимот ўтказди",
                    "info": "Семинар давомида иштирокчиларнинг эътибори Ўзбекистонга ташриф буюриш учун тавсия этилган энг машҳур жойлар акс этган «Welcome to Uzbekistan» ва «Ўзбекистон шаҳарлари» номли видеоклипларга қаратилди.",
                    "views": 64,
                    "comment_count": 0,
                    "label": "important",
                    "image": "http://static.xabar.lc/crop/m/2/7/medium_normal_2786856261.jpg",
                    "image_caption": "",
                    "image_source": "Фото: «Ўзбекистон ҳаво йўллари» МАК",
                    "categories": "5a09b5a62f19e05a6be771f7",
                    "view_url": "https://www.xabar.lc/post/ozbekiston-havo-yollari-tailandda-taqdimot-otkazdi",
                    "audio_url": false,
                    "video_url": false,
                    "youtube_url": null,
                    "published_on": 1517986759,
                    "updated_at": 1518000188,
                    "has_audio": false,
                    "has_video": false,
                    "has_gallery": false,
                    "has_russian": false,
                    "has_priority": true
                }
            ],
            "gallery": [
            ]
        },
        "success": true,
        "status": 200
    }

###7.Get authors list

Request: `GET: author/list?page=1`

Response: 

    {
      "items": [
        {
          "id": "5a2d9ee301abe2f67d0dd73c",
          "fullname": "Davronbek Parmonov#|#Давронбек Пармонов#|#Davronbek Parmonov",
          "job": "frilanser#|#фрилансер#|#frilanser",
          "intro": "Xabardormisan — qudratlisan!#|#Хабардормисан — қудратлисан!#|#Xabardormisan — qudratlisan!",
          "description": "<p style=\"text-align: justify;\">Frilanserman. Hozir Buxarest iqtisod akademiyasida tahsil olayapman. Iqtisod va axborot texnologiyalariga qiziqaman va shu sohalarda o&lsquo;z ustimda ishlashga harakat qilaman.</p>#|#<p style=\"text-align: justify;\">Фрилансерман. Ҳозир Бухарест иқтисод академиясида таҳсил олаяпман. Иқтисод ва ахборот технологияларига қизиқаман ва шу соҳаларда ўз устимда ишлашга ҳаракат қиламан.</p>#|#<p style=\"text-align: justify;\">Frilanserman. Hozir Buxarest iqtisod akademiyasida tahsil olayapman. Iqtisod va axborot texnologiyalariga qiziqaman va shu sohalarda o&lsquo;z ustimda ishlashga harakat qilaman.</p>",
          "posts": [
            "5a3983a001abe2b65adfa045"
          ],
          "email": "parmonov.davron@gmail.com",
          "facebook": "https://www.facebook.com/bakvendor/",
          "count_posts": 1
        },
        {
          "id": "5a2ea30701abe22a32b1978e",
          "fullname": "Nurbek Alimov#|#Нурбек Алимов#|#Nurbek Alimov",
          "job": "xalqaro iqtisodiy munosabatlar#|#халқаро иқтисодий муносабатлар#|#xalqaro iqtisodiy munosabatlar",
          "intro": "Oz narsani qurbon qilgan odam, oz narsaga erishadi. Ko‘p narsani xohlagan odam esa ko‘proq narsadan voz kechishi kerakligini biladi.#|#Оз нарсани қурбон қилган одам, оз нарсага эришади. Кўп нарсани хоҳлаган одам эса кўпроқ нарсадан воз кечиши кераклигини билади.#|#Oz narsani qurbon qilgan odam, oz narsaga erishadi. Ko‘p narsani xohlagan odam esa ko‘proq narsadan voz kechishi kerakligini biladi.",
          "description": "<p style=\"text-align: justify;\">1989-yili Toshkent shahrida tug&lsquo;ilganman. 2010-yili Moskva davlat xalqaro munosabatlar instituti (Rossiya), 2012-yili Sevilya universiteti (Ispaniya)ni tamomladim. 2016<em>-</em>2017-yillarda O&lsquo;zbekiston Respublikasi Prezidenti huzuridagi davlat boshqaruvi akademiyasi tinglovchisi bo&lsquo;ldim. Uylanganman, bir nafar farzandim bor. Ayni paytda Belgiyada yashayman.</p>#|#<p style=\"text-align: justify;\">1989 йили Тошкент шаҳрида туғилганман. 2010 йили Москва давлат халқаро муносабатлар институти (Россия), 2012 йили Севиля университети (Испания)ни тамомладим. 2016-2017 йилларда Ўзбекистон Республикаси Президенти ҳузуридаги давлат бошқаруви академияси тингловчиси бўлдим. Уйланганман, бир нафар фарзандим бор. Айни пайтда Белгияда яшайман.</p>#|#<p style=\"text-align: justify;\">1989-yili Toshkent shahrida tug&lsquo;ilganman. 2010-yili Moskva davlat xalqaro munosabatlar instituti (Rossiya), 2012-yili Sevilya universiteti (Ispaniya)ni tamomladim. 2016<em>-</em>2017-yillarda O&lsquo;zbekiston Respublikasi Prezidenti huzuridagi davlat boshqaruvi akademiyasi tinglovchisi bo&lsquo;ldim. Uylanganman, bir nafar farzandim bor. Ayni paytda Belgiyada yashayman.</p>",
          "posts": [
            "5a39bb9601abe2f0670b5ac1",
            "5a3c2d8701abe2bb737d4546",
            "5a3eccce01abe2740c5f2e82"
          ],
          "email": "alimov@xabar.uz",
          "facebook": "https://www.facebook.com/Yomon.yigit",
          "count_posts": 3
        }
      ],
      "page": 1
      "success": true,
      "status": 200
    }
    
###7.Get author data

Request: `GET: author/posts?id=5a41144601abe2ca280166aa&page=0`

Response:

    {
      "items": [
        {
          "id": "5a453fc201abe23a79b2d732",
          "short_id": "iuq",
          "title": "«Nazira» — 1944—2007-yillarda yashab o‘tgan qashqadaryolik birinchi tadbirkor ayol haqidagi ta’sirli film",
          "info": "Shu yilning 19 dekabr kuni rejissyor Jahongir Qosimov suratga olgan «Nazira» filmining premerasiga borish nasib qildi. Filmni rahmatli Nazira Shodmonovaning o‘g‘li moliyalashtirgan. Film 1944—2007-yillarda yashab o‘tgan jizzaxlik birinchi tadbirkor ayol Nazira Shodmonovaning hayot yo‘liga bag‘ishlangan.",
          "views": 146,
          "views_l3d": 2,
          "views_l7d": null,
          "views_l30d": null,
          "views_today": null,
          "comment_count": 0,
          "label": "regular",
          "image": "https://static.xabar.lc/crop/m/4/2/medium_normal_4221729618.jpg",
          "image_caption": "«Nazira» filmining premerasi",
          "image_source": "Foto: «Xabar.uz»",
          "categories": "5a004eef2f19e0577920710f",
          "view_url": "https://www.xabar.lc/post/jizzaxlik-birinchi-tadbirkor-ayol-haqidagi-tasirli-film",
          "audio_url": false,
          "video_url": false,
          "youtube_url": null,
          "published_on": 1514488200,
          "updated_at": 1514522211,
          "has_audio": false,
          "has_video": false,
          "has_gallery": false,
          "has_russian": false,
          "has_priority": false
        },
        {
          "id": "5a505c8e01abe2a238f1ec91",
          "short_id": "knz",
          "title": "Ijtimoiy innovatsiya: ta’lim tizimi muammolarini yechishda xalq yordami",
          "info": "Bu maqolani yozishni o‘zimga bir yil avval maqsad qilgan edim. Ta’lim tizimida xalq, ota-onalar yordami borasidagi fikrlarimni dars mavzusi doirasida talabalarim bilan bo‘lishdim; ba’zi davlat organlari hodimlariga ham bir-ikki g‘oyamni yetkazdim; imkon bo‘lganda do‘stlarim, tanishlarim bilan muhokama qildim.",
          "views": 334,
          "views_l3d": 18,
          "views_l7d": null,
          "views_l30d": null,
          "views_today": null,
          "comment_count": 0,
          "label": "regular",
          "image": "https://static.xabar.lc/crop/m/3/0/medium_normal_3020842256.png",
          "image_caption": "",
          "image_source": "Foto: «Killerinnovations.com»",
          "categories": "5a50664401abe26d41f1ec91",
          "view_url": "https://www.xabar.lc/post/ijtimoiy-innovaciya-talim-tizimi-muammolari",
          "audio_url": false,
          "video_url": false,
          "youtube_url": null,
          "published_on": 1515218400,
          "updated_at": 1515257788,
          "has_audio": false,
          "has_video": false,
          "has_gallery": false,
          "has_russian": false,
          "has_priority": false
        }
      ],
      "page": 0,
      "author": "5a41144601abe2ca280166aa",
      "success": true,
      "status": 200
    }
    
   
###8.Login user

Request: `POST: account/login`

Request body: 

    {
        "email": "test@mail.ru"
        "password": "test123"
    }
    
Response: 

    {
      "email": "test@mail.ru",
      "fullname": "Test User",
      "token": "IwPt_9xU87GtzsStzyiP4yBbvd-VQlWy",
      "success": true,
      "status": 200
    }
   
###9.Signup user

Request: `POST: account/signup`

Request body: 

    {
        "fullname": "Test User"
        "email": "test@mail.ru"
        "password": "test123"
        "confirmation": "test123"
    }
    
Response: 

    {
      "email": "test@mail.ru",
      "fullname": "Test User",
      "token": "IwPt_9xU87GtzsStzyiP4yBbvd-VQlWy",
      "success": true,
      "status": 200
    }
    
###11.Social login

Request: `POST: account/auth`

Request body:
 
    {
        "fullname": "Test User"
        "email": "test@mail.ru"
        "id": "121412515151"
        "client": "facebook"
        "login": "121412515151"
        "avatar_url": "https://scontent.xx.fbcdn.net/v/t1.0-1/p50x50/27544834_101664507318290_2470208817670092508_n.jpg?oh=6a058d2e619ec670966f66b2558f0d2e&oe=5B1F9427"
    }
    
Response: 

    {
      "email": "test@mail.ru",
      "fullname": "Test User",
      "token": "IwPt_9xU87GtzsStzyiP4yBbvd-VQlWy",
      "login": "121412515151",
      "client": "facebook",
      "avatar_url": "https://scontent.xx.fbcdn.net/v/t1.0-1/p50x50/27544834_101664507318290_2470208817670092508_n.jpg?oh=6a058d2e619ec670966f66b2558f0d2e&oe=5B1F9427"
      "success": true,
      "status": 200
    }
    