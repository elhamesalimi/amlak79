import {CHANGE_DISABLED_CITY} from "../api/strings";
// reducer which is a pure function
export default (state = [], action) => {
    console.log('CHANGE_DISABLED_CITY1:',action);

    switch (action.type) {

        case CHANGE_DISABLED_CITY:
            console.log('CHANGE_DISABLED_CITY2: ',action);
            const disabled = action.payload;
            return disabled;
        default:
            console.log('return default');
            return state;
    }
};