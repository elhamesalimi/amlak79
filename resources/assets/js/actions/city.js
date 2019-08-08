import {CHANGE_SELECTED_CITY} from "../api/strings";
import {CHANGE_DISABLED_CITY} from "../api/strings";

const changeSelectedCityHelper = (selectedCitySlug) => ({
    type: CHANGE_SELECTED_CITY,
    payload: selectedCitySlug || "qazvin"
});
const changeDisabledCityHelper = (disabled) => ({
    type: CHANGE_DISABLED_CITY,
    payload: disabled || false
});

export function changeSelectedCity(selectedCitySlug ){
    console.log('selectedCitySlugAction:',selectedCitySlug);
   return (dispatch)=>(
       dispatch(changeSelectedCityHelper(selectedCitySlug))
);
}
export function changeDisabledCity(disabled ){
    console.log('changeDisabledCityAction: is ok');
   return (dispatch)=>(
       dispatch(changeDisabledCityHelper(disabled))
);
}