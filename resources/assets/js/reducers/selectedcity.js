import {CHANGE_SELECTED_CITY} from "../api/strings";
// reducer which is a pure function
export default (state = [], action) => {
    console.log('selectedCitySlugReducer1:',action);

    switch (action.type) {

        case CHANGE_SELECTED_CITY:
            console.log('selectedCitySlugReducer2: ',action);
            const selectedCity = action.payload;
            return selectedCity;
        default:
            console.log('return default');
            return state;
    }
};