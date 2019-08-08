import {ACCESS_TOKEN, FILTER_ESTATES} from '../api/strings'
import axios from 'axios';

const filterEstatesHelper = (estates) => ({
    type: FILTER_ESTATES,
    estates
});

export function filterEstates( city, filters = {}) {
    return (dispatch) => {
        const params = {
            city: city || 'qazvin',
            ...filters
        };
        const access_token = window.localStorage.getItem(ACCESS_TOKEN);
        const headers = {Accept: "application/json", Authorization: `Bearer ${access_token}`};
        axios.get('/api/estates/', {headers, params}).then(function (response) {
            dispatch(filterEstatesHelper(response.data));
        });
    }
}
