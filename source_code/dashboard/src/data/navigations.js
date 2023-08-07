import React from "react";
import { RiDashboardLine } from 'react-icons/ri';
import { PiArticleMediumFill } from 'react-icons/pi';
import { MdOutlineReviews, MdSettingsSuggest } from 'react-icons/md';
import { TbDeviceIpadStar } from 'react-icons/tb';

const navigations = [
    {
        target: "./",
        icon: React.createElement(RiDashboardLine),
        label: "Dashboard",
        isActive: true
    },
    {
        target: "./articles",
        icon: React.createElement(PiArticleMediumFill),
        label: "Articles",
        isActive: false
    },
    {
        target: "./reviews",
        icon: React.createElement(MdOutlineReviews),
        label: "Reviews",
        isActive: false
    },
    {
        target: "./ads",
        icon: React.createElement(TbDeviceIpadStar),
        label: "Ads",
        isActive: false
    },
    {
        target: "./settings",
        icon: React.createElement(MdSettingsSuggest),
        label: "Settings",
        isActive: false
    }
];

export default navigations;
