<?php
return [
    [
        'id' => 1,
        'name' => 'Sữa, Bánh Kẹo, Thực phẩm',
        'special' => 1,
        'special_min_amount' => 0,
        'condition_data' => null
    ],
    [
        'id' => 2,
        'name' => 'Quần áo, phụ kiện thời trang',
        'special' => 1,
        'special_min_amount' => 300,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 450,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ]
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ]
    ],
    [
        'id' => 3,
        'name' => 'Vali li thường',
        'special' => 1,
        'special_min_amount' => 0,
        'condition_data' => null
    ],
    [
        'id' => 4,
        'name' => 'Vali  loại cao cấp  (Hermes, Gucci, Louis Vuiton... )',
        'special' => 1,
        'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 450,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ]
    ],
    [
        'id' => 5,
        'name' => 'Túi Xách loại thường',
        'special' => 1,
        'special_min_amount' => 300,
        'condition_data' => null
    ],
    [
        'id' => 6,
        'name' => 'Túi Xách loại cao cấp  (Hermes, Gucci, Louis Vuiton... )',
        'special' => 1,
        'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 450,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ]
    ],
    [
        'id' => 7,
        'name' => 'Ví, Thắt Lưng',
        'special' => 1,
        'special_min_amount' => 300,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 450,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ]
    ],
    [
        'id' => 8,
        'name' => 'Sách Chung',
        'special' => 1,
        'special_min_amount' => 300,
        'condition_data' => null
    ],
    [
        'id' => 9,
        'name' => 'Sách Báo - Tạp Chí (Không Liên Quan Đến Giáo Dục)',
        'special' => 1,
        'special_min_amount' => 3000,
        'condition_data' => null
    ],
    [
        'id' => 10,
        'name' => 'Đĩa (CD, DVD, than…)',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => null
    ],
    [
        'id' => 11,
        'name' => 'Gậy Gậy Bida,  Cần Câu Cá',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => null
    ],
    [
        'id' => 12,
        'name' => 'Vợt Tennis, Vợt Cầu Lông,',
        'special' => 1,
        'special_min_amount' => 0,
        'condition_data' => null
    ],
    [
        'id' => 13,
        'name' => 'Zippo',
        'special' => 1,
        'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 450,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ]
    ],
    [
        'id' => 14,
        'name' => 'Giày dép',
        'special' => 1,
        'special_min_amount' => 300,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 450,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ]
    ],
    [
        'id' => 15,
        'name' => 'Mắt Kính',
        'special' => 1,
        'special_min_amount' => 300,
        'condition_data' => null
    ],
    [
        'id' => 16,
        'name' => 'Trang Sức',
        'special' => 1,
        'special_min_amount' => 300,
        'condition_data' => null
    ],
    [
        'id' => 17,
        'name' => 'Đồng Hồ đeo tay',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => null,
                'type' => 'P',
                'value' => 5,
                'unit' => 'price',
                'minValue' => 6,
                'minUnit' => 'quantity'
            ],
        ]
    ],
    [
        'id' => 18,
        'name' => 'Đồng hồ treo tường, đồng hồ bàn',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => null,
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ]
    ],
    [
        'id' => 19,
        'name' => 'Mỹ Phẩm, Sữa Tắm, Lotion, Kem Dưỡng Da, Kem Chống Nắng',
        'special' => 0,
        'special_min_amount' => 0,
        'condition_data' => null
    ],
    [
        'id' => 20,
        'name' => 'Thuốc, Thực Phẩm Chức Năng, Vitamin…',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => null
    ],
    [
        'id' => 21,
        'name' => 'Màn hình máy tính',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 345,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '<'
                    ],
                ],
                'type' => 'F',
                'value' => 10,
                'unit' => 'quantity'
            ],
            [
                'conditions' => [
                    [
                        'value' => 345,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ]
    ],
    [
        'id' => 22,
        'name' => 'Linh Kiện Máy Tính (RAM, Ổ cứng, Chuột, bàn phím)',
        'special' => 1,
        'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 345,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ],
    ],
    [
        'id' => 23,
        'name' => 'Máy in, máy scan, máy chiếu',
        'special' => 1,
        'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 345,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '<'
                    ],
                ],
                'type' => 'F',
                'value' => 10,
                'unit' => 'quantity'
            ],
            [
                'conditions' => [
                    [
                        'value' => 345,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ]
    ],
    [
        'id' => 24,
        'name' => 'Robot',
        'special' => 1,
        'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 345,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ],
    ],
    [
        'id' => 25,
        'name' => 'Máy ảnh, Máy quay phim, Lens',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 345,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '<'
                    ],
                ],
                'type' => 'F',
                'value' => 10,
                'unit' => 'quantity'
            ],
            [
                'conditions' => [
                    [
                        'value' => 345,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ]
    ],
    [
        'id' => 26,
        'name' => 'Fly cam',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 345,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '<'
                    ],
                ],
                'type' => 'F',
                'value' => 15,
                'unit' => 'quantity'
            ],
            [
                'conditions' => [
                    [
                        'value' => 345,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ]
    ],
    [
        'id' => 27,
        'name' => 'Ống nhòm',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 345,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '<'
                    ],
                ],
                'type' => 'F',
                'value' => 10,
                'unit' => 'quantity'
            ],
            [
                'conditions' => [
                    [
                        'value' => 345,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ]
    ],
    [
        'id' => 28,
        'name' => 'Hệ Thống Nhạc Tại Nhà (Home Theater System)',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 345,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ],
    ],
    [
        'id' => 29,
        'name' => 'Loa đài - Amplifier & Receiver (Trên 10kg)',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 345,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ],
    ],
    [
        'id' => 30,
        'name' => 'Loa đài - Amplifier & Receiver (Dưới 10kg)',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 345,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ]
        ],
    ],
    [
        'id' => 31,
        'name' => 'Loa đài - Củ loa',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 345,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ],
    ],
    [
        'id' => 32,
        'name' => 'Loa đài - Hệ thống nhạc tại nhà (Home theater system)',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 345,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ],
    ],
    [
        'id' => 33,
        'name' => 'Loa đài - Loa mới',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 345,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ],
    ],
    [
        'id' => 34,
        'name' => 'Loa đài - Loa cũ, loa thùng to',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 345,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ],
    ],
    [
        'id' => 35,
        'name' => 'Loa đài - Loa nhỏ, xách tay - Portable speakers',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 345,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ],
    ],
    [
        'id' => 36,
        'name' => 'Loa đài - Recorder (loại > 10kg)',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 345,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ],
    ],
    [
        'id' => 37,
        'name' => 'Đĩa (CD, DVD, than…)',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 345,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ],
    ],
    [
        'id' => 38,
        'name' => 'Radio trong ô tô',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 345,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ],
    ],
    [
        'id' => 39,
        'name' => 'Thiết bị DJ, MIXER….',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 345,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ],
    ],
    [
        'id' => 40,
        'name' => 'Thiết Bị Âm Thanh, Video Game,',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 345,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ],
    ],
    [
        'id' => 41,
        'name' => 'Xbox PS3, PS2 , Wii, Nitendo',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 345,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ],
    ],
    [
        'id' => 42,
        'name' => 'Xe Mô Hình, Máy Bay Đồ Chơi, đồ chơi điện…',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 345,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ],
    ],
    [
        'id' => 43,
        'name' => 'Linh Kiện Xe Đạp, Xe Máy (Các Bộ Phận Không Có Số Khung, Số Máy)',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 345,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>='
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ],
    ],
    [
        'id' => 44,
        'name' => 'Xe đạp nguyên chiếc',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => null,
                'type' => 'F',
                'value' => 100,
                'unit' => 'quantity'
            ],
        ],
    ],
    [
        'id' => 45,
        'name' => 'Khung xe',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => null,
                'type' => 'F',
                'value' => 50,
                'unit' => 'quantity'
            ],
        ],
    ],
    [
        'id' => 46,
        'name' => 'Vành xe máy, Phụ tùng xe máy to, nặng',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => null
    ],
    [
        'id' => 47,
        'name' => 'Xe máy (Linh kiện, các bộ phận không có số khung, số máy)',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => null,
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ],
    ],
    [
        'id' => 48,
        'name' => 'Linh kiện ô tô',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => null,
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ],
    ],
    [
        'id' => 49,
        'name' => 'Mâm ô tô',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => null,
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ],
    ],
    [
        'id' => 50,
        'name' => 'Đàn Guitar Điện, Organ, Piano',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => null,
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ],
    ],
    [
        'id' => 51,
        'name' => 'Gậy Golf thường',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => null,
                'type' => 'F',
                'value' => 10,
                'unit' => 'quantity'
            ],
        ],
    ],
    [
        'id' => 52,
        'name' => 'Iphone XS và các dòng tương đương',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => null,
                'type' => 'F',
                'value' => 55,
                'unit' => 'quantity'
            ],
        ],
    ],
    [
        'id' => 53,
        'name' => 'Iphone X / Max và các dòng tương đương',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => null,
                'type' => 'F',
                'value' => 55,
                'unit' => 'quantity'
            ],
        ],
    ],
    [
        'id' => 54,
        'name' => 'Iphone 7, 7Plus, 8, 8PLUS và các dòng tương đương',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => null,
                'type' => 'F',
                'value' => 55,
                'unit' => 'quantity'
            ],
        ],
    ],
    [
        'id' => 55,
        'name' => 'Iphone 6s, 6plus, Samsung Galaxy S6, S7, Iphone 6,… và các dòng tương đương',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => null,
                'type' => 'F',
                'value' => 55,
                'unit' => 'quantity'
            ],
        ],
    ],
    [
        'id' => 56,
        'name' => 'Iphone 4/5 và các dòng tương đương',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => null,
                'type' => 'F',
                'value' => 25,
                'unit' => 'quantity'
            ],
        ],
    ],
    [
        'id' => 57,
        'name' => 'cac san pham phone khac nhu Samsung , LG ... $40$',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => null,
                'type' => 'F',
                'value' => 40,
                'unit' => 'quantity'
            ],
        ],
    ],
    [
        'id' => 58,
        'name' => 'Điện thoại thường',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => null,
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ],
    ],
    [
        'id' => 59,
        'name' => 'Tablet, Ipad',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => null,
                'type' => 'F',
                'value' => 25,
                'unit' => 'quantity'
            ],
        ],
    ],
    [
        'id' => 60,
        'name' => 'Kindle Amazon, Nook, E-Reader <100$',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => null,
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ],
    ],
    [
        'id' => 61,
        'name' => 'Ipod',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => null,
                'type' => 'F',
                'value' => 6,
                'unit' => 'quantity'
            ],
        ],
    ],
    [
        'id' => 62,
        'name' => 'Laptop, máy tính all in one, Desktop, Case',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 1000,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '<='
                    ],
                ],
                'type' => 'F',
                'value' => 50,
                'unit' => 'quantity'
            ],
            [
                'conditions' => [
                    [
                        'value' => 1000,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>'
                    ],
                ],
                'type' => 'P',
                'value' => 7,
                'unit' => 'price'
            ],
        ],
    ],
    [
        'id' => 63,
        'name' => 'Nước Hoa',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => null,
                'type' => 'F',
                'value' => 3,
                'unit' => 'quantity'
            ],
        ],
    ],
    [
        'id' => 64,
        'name' => 'Hóa phẩm, chất lỏng',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => null,
                'type' => 'F',
                'value' => 2,
                'unit' => 'quantity'
            ],
        ],
    ],
    [
        'id' => 65,
        'name' => 'Thiết bị thuốc lá điện tử',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => null,
                'type' => 'F',
                'value' => 5,
                'unit' => 'quantity'
            ],
        ],
    ],
    [
        'id' => 66,
        'name' => 'Tinh dầu thuốc lá điện tử',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => null,
                'type' => 'F',
                'value' => 1,
                'unit' => 'quantity'
            ],
        ],
    ],
    [
        'id' => 67,
        'name' => 'Tinh dầu nước hoa, tinh dầu thảo mộc',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => null,
                'type' => 'F',
                'value' => 1,
                'unit' => 'quantity'
            ],
        ],
    ],
    [
        'id' => 68,
        'name' => 'Đồ y tế cá nhân: máy đo huyết áp cá nhân, máy đo đường huyết....',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => null,
                'type' => 'F',
                'value' => 5,
                'unit' => 'quantity'
            ],
        ],
    ],
    [
        'id' => 69,
        'name' => 'Thiết bị y tế sử dụng trong bệnh viện',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => null,
                'type' => 'P',
                'value' => 10,
                'unit' => 'price',
                'minValue' => 50,
            ],
        ],
    ],
    [
        'id' => 70,
        'name' => 'Cigar Chỉ nhập 1 hộp ( tôi đa 25 điếu )',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => [
                    [
                        'value' => 250,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '<='
                    ],
                ],
                'type' => 'F',
                'value' => 10,
                'unit' => 'quantity'
            ],
            [
                'conditions' => [
                    [
                        'value' => 250,
                        'key' => 'price',
                        'type' => 'int',
                        'operator' => '>'
                    ],
                ],
                'type' => 'P',
                'value' => 5,
                'unit' => 'price'
            ],
        ],
    ],
    [
        'id' => 71,
        'name' => 'Hàng cồng kềnh (Oversize, Overweight máy giặt, máy hút mùi… Không nhập Bumper)',
        'special' => 1,
        'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => null,
                'type' => 'F',
                'value' => 2,
                'unit' => 'weight'
            ],
        ],
    ],
    [
        'id' => 72,
        'name' => 'Kính thiên văn, Kính viễn vọng và các loại tương tự',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => [
            [
                'conditions' => null,
                'type' => 'P',
                'value' => 10,
                'unit' => 'price',
                'minValue' => 10,
            ],
        ],
    ],
    [
        'id' => 73,
        'name' => 'Hàng cũ',
        'special' => 1, 'special_min_amount' => 0,
        'condition_data' => null
    ],
];