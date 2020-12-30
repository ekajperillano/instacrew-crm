import _ from 'lodash';
import accounting from 'accounting'
import moment from 'moment';
import { DATE_FORMAT } from 'configs/constants.js';

export function getDates(startDate, stopDate) {
    var dateArray = [];
    var currentDate = moment(startDate);
    while (currentDate <= moment(stopDate)) {
        dateArray.push( moment(currentDate).format('YYYY-MM-DD') )
        currentDate = moment(currentDate).add(1, 'days');
    }
    return dateArray;
}


export function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
      color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

export function formatNumber (value, precision = 2) {
    return accounting.formatNumber(value, precision)
}

export function allCap (value) {
    if(value) {
      return value.toUpperCase()
    }
    return null;
}

export function formatDate (value, fmt = DATE_FORMAT) {
    return (value == null)
      ? ''
      : moment(value, 'YYYY-MM-DD').format(fmt)
}

export function inArray(haystack, needle) {
    return haystack.findIndex(hay =>  _.toLower(hay) == _.toLower(needle)) > -1
}

export function refreshDatatable(app, datatable) {
    app.$nextTick( () => {
        app.$refs[datatable].refresh();
    });
}