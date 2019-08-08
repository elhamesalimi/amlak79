import React from "react";
import Creatable from 'react-select/lib/Creatable';
import {connect} from "react-redux";
import {filterEstates} from "../actions/estates";
import {withRouter} from 'react-router-dom';
// import '../script/jquery.min';
import ReactMultiSelectCheckboxes from 'react-multiselect-checkboxes';
import axios from 'axios'

const rent_min_total_prices = [
    {value: "", label: "انتخاب کنید..."},
    {value: "0", label: "مجانی"},
    {value: "1000000", label: " 1 میلیون"},
    {value: "3000000", label: " 3 میلیون"},
    {value: "5000000", label: " 5 میلیون"},
    {value: "7000000", label: " 7 میلیون"},
    {value: "10000000", label: " 10 میلیون"},
    {value: "15000000", label: " 15 میلیون"},
    {value: "20000000", label: " 20 میلیون"},
    {value: "25000000", label: " 25 میلیون"},
    {value: "30000000", label: " 30 میلیون"},
    {value: "35000000", label: " 35 میلیون"},
    {value: "40000000", label: " 40 میلیون"},
    {value: "50000000", label: " 50 میلیون"},
    {value: "60000000", label: " 60 میلیون"},
    {value: "70000000", label: " 70 میلیون"},
    {value: "80000000", label: " 80 میلیون"},
    {value: "90000000", label: " 90 میلیون"},
    {value: "100000000", label: " 100 میلیون"},
    {value: "120000000", label: " 120 میلیون"},
    {value: "140000000", label: " 140 میلیون"},
    {value: "160000000", label: " 160 میلیون"},
    {value: "180000000", label: " 180 میلیون"},
    {value: "200000000", label: " 200 میلیون"},
    {value: "250000000", label: " 250 میلیون"},
    {value: "300000000", label: " 300 میلیون"},
    {value: "400000000", label: " 400 میلیون"},
    {value: "500000000", label: " 500 میلیون"},
];
const rent_max_total_prices = [
    {value: "", label: "انتخاب کنید..."},
    {value: "0", label: " مجانی"},
    {value: "1000000", label: " 1 میلیون"},
    {value: "3000000", label: " 3 میلیون"},
    {value: "5000000", label: " 5 میلیون"},
    {value: "7000000", label: " 7 میلیون"},
    {value: "10000000", label: " 10 میلیون"},
    {value: "15000000", label: " 15 میلیون"},
    {value: "20000000", label: " 20 میلیون"},
    {value: "25000000", label: " 25 میلیون"},
    {value: "30000000", label: " 30 میلیون"},
    {value: "35000000", label: " 35 میلیون"},
    {value: "40000000", label: " 40 میلیون"},
    {value: "50000000", label: " 50 میلیون"},
    {value: "60000000", label: " 60 میلیون"},
    {value: "70000000", label: " 70 میلیون"},
    {value: "80000000", label: " 80 میلیون"},
    {value: "90000000", label: " 90 میلیون"},
    {value: "100000000", label: " 100 میلیون"},
    {value: "120000000", label: " 120 میلیون"},
    {value: "140000000", label: " 140 میلیون"},
    {value: "160000000", label: " 160 میلیون"},
    {value: "180000000", label: " 180 میلیون"},
    {value: "200000000", label: " 200 میلیون"},
    {value: "250000000", label: " 250 میلیون"},
    {value: "300000000", label: " 300 میلیون"},
    {value: "400000000", label: " 400 میلیون"},
    {value: "500000000", label: " 500 میلیون"},
    {value: "5000000000", label: " بیشتر از 500میلیون"},
];
const rent_max_prices = [
    {value: "0", label: 'مجانی'},
    {value: "50000", label: '50 هزار'},
    {value: "100000", label: '100 هزار'},
    {value: "150000", label: '150 هزار'},
    {value: "200000", label: '200 هزار'},
    {value: "250000", label: '250 هزار'},
    {value: "300000", label: '300 هزار'},
    {value: "400000", label: '400 هزار'},
    {value: "500000", label: '500 هزار'},
    {value: "600000", label: '600 هزار'},
    {value: "700000", label: '700 هزار'},
    {value: "800000", label: '800 هزار'},
    {value: "900000", label: '900 هزار'},
    {value: "1000000", label: '1 میلیون'},
    {value: "1200000", label: '1.2 میلیون'},
    {value: "1500000", label: '1.5میلیون'},
    {value: "1800000", label: '1.8'},
    {value: "2000000", label: '2 میلیون'},
    {value: "2500000", label: 'میلیون 2.5'},
    {value: "3000000", label: '3 میلیون'},
    {value: "4000000", label: '4 میلیون'},
    {value: "5000000", label: '5 میلیون'},
    {value: "150000000", label: 'بیشتر از 5 میلیون'},
];
const total_prices = [
    {value: "", label: "انتخاب کنید ..."},
    {value: "100000000", label: '100 میلیون'},
    {value: "150000000", label: '150 میلیون'},
    {value: "200000000", label: '200 میلیون'},
    {value: "250000000", label: '250 میلیون'},
    {value: "300000000", label: '300 میلیون'},
    {value: "400000000", label: '400 میلیون'},
    {value: "500000000", label: '500 میلیون'},
    {value: "600000000", label: '600 میلیون'},
    {value: "700000000", label: '700 میلیون'},
    {value: "800000000", label: '800 میلیون'},
    {value: "900000000", label: '900 میلیون'},
    {value: '1000000000', label: '1 میلیارد'},
    {value: '1200000000', label: '1.2 میلیارد '},
    {value: '1400000000', label: '1.4 میلیارد '},
    {value: '1600000000', label: '1.6 میلیارد '},
    {value: '1800000000', label: '1.8 میلیارد '},
    {value: '2000000000', label: '2 میلیارد'},
    {value: '2200000000', label: '2.2 میلیارد '},
    {value: '2400000000', label: '2.4 میلیارد '},
    {value: '2600000000', label: '2.6 میلیارد '},
    {value: '2800000000', label: '2.8 میلیارد '},
    {value: '3000000000', label: '3 میلیارد'},
    {value: '3500000000', label: '3.5 میلیارد '},
    {value: '4000000000', label: '4 میلیارد'},
    {value: "100000000000", label: 'بیشتر 4 میلیارد'},
];
const prices = [
    {value: "", label: "انتخاب کنید ..."},
    {value: '1', label: 'کمتر از 100 میلیون'},
    {value: '100000000', label: '100 میلیون'},
    {value: '200000000', label: '200 میلیون'},
    {value: '250000000', label: '250 میلیون'},
    {value: '300000000', label: '300 میلیون'},
    {value: '400000000', label: '400 میلیون'},
    {value: '500000000', label: '500 میلیون'},
    {value: '600000000', label: '600 میلیون'},
    {value: '700000000', label: '700 میلیون'},
    {value: '800000000', label: '800 میلیون'},
    {value: '900000000', label: '900 میلیون'},
    {value: '1000000000', label: '1 میلیارد'},
    {value: '1200000000', label: '1.2 میلیارد '},
    {value: '1400000000', label: '1.4 میلیارد '},
    {value: '1600000000', label: '1.6 میلیارد '},
    {value: '1800000000', label: '1.8 میلیارد '},
    {value: '2000000000', label: '2 میلیارد'},
    {value: '2200000000', label: '2.2 میلیارد '},
    {value: '2400000000', label: '2.4 میلیارد '},
    {value: '2600000000', label: '2.6 میلیارد '},
    {value: '2800000000', label: '2.8 میلیارد '},
    {value: '3000000000', label: '3 میلیارد'},
    {value: '3500000000', label: '3.5 میلیارد '},
    {value: '4000000000', label: '4 میلیارد'},
];
const rent_min_prices = [
    {value: "0", label: 'مجانی'},
    {value: "50000", label: '50 هزار'},
    {value: "100000", label: '100 هزار'},
    {value: "150000", label: '150 هزار'},
    {value: "200000", label: '200 هزار'},
    {value: "250000", label: '250 هزار'},
    {value: "300000", label: '300 هزار'},
    {value: "400000", label: '400 هزار'},
    {value: "500000", label: '500 هزار'},
    {value: "600000", label: '600 هزار'},
    {value: "700000", label: '700 هزار'},
    {value: "800000", label: '800 هزار'},
    {value: "1000000", label: '1 میلیون'},
    {value: "1200000", label: '1.2 میلیون'},
    {value: "1500000", label: '1.5 میلیون'},
    {value: "1800000", label: '1.8 میلیون'},
    {value: "2000000", label: '2 میلیون'},
    {value: "2500000", label: '2.5 میلیون'},
    {value: "3000000", label: '3 میلیون'},
    {value: "4000000", label: '4 میلیون'},
];
const min_areas = [
    {value: "", label: "انتخاب کنید ..."},
    {value: "1", label: 'کمتر از 20'},
    {value: "20", label: '20 متر'},
    {value: "30", label: '30 متر'},
    {value: "40", label: '40 متر'},
    {value: "50", label: '50 متر'},
    {value: "60", label: '60 متر'},
    {value: "70", label: '70 متر'},
    {value: "80", label: '80 متر'},
    {value: "90", label: '90 متر'},
    {value: "100", label: '100 متر'},
    {value: "110", label: '110 متر'},
    {value: "120", label: '120 متر'},
    {value: "130", label: '130 متر'},
    {value: "140", label: '140 متر'},
    {value: "150", label: '150 متر'},
    {value: "170", label: '170 متر'},
    {value: "190", label: '190 متر'},
    {value: "210", label: '210 متر'},
    {value: "230", label: '230 متر'},
    {value: "250", label: '250 متر'},
    {value: "300", label: '300 متر'},
    {value: "350", label: '350 متر'},
    {value: "450", label: '450 متر'},
];
const max_areas = [
    {value: "", label: "انتخاب کنید ..."},
    {value: "20", label: '20 متر'},
    {value: "30", label: '30 متر'},
    {value: "40", label: '40 متر'},
    {value: "50", label: '50 متر'},
    {value: "60", label: '60 متر'},
    {value: "70", label: '70 متر'},
    {value: "80", label: '80 متر'},
    {value: "90", label: '90 متر'},
    {value: "100", label: '100 متر'},
    {value: "110", label: '110 متر'},
    {value: "120", label: '120 متر'},
    {value: "130", label: '130 متر'},
    {value: "140", label: '140 متر'},
    {value: "150", label: '150 متر'},
    {value: "170", label: '170 متر'},
    {value: "190", label: '190 متر'},
    {value: "210", label: '210 متر'},
    {value: "230", label: '230 متر'},
    {value: "250", label: '250 متر'},
    {value: "300", label: '300 متر'},
    {value: "350", label: '350 متر'},
    {value: "450", label: '450 متر'},
    {value: "'99999999", label: 'بیشتر از 450'},
];
const plans = [
    {value: "1", label: "شمالی"},
    {value: "2", label: "جنوبی"},
    {value: "3", label: "شرقی-غربی"},
    {value: "4", label: "دو کله"},
    {value: "5", label: "دو نبش"},
    {value: "6", label: "سه نبش"},
];

const multiSelectCustomStyle = {
    menu: (styles) => ({
        ...styles,
        top: '100%',
        position: 'inherit !important',
        backgroundColor: 'hsl(0,0%,100%)',
        borderRadius: '4px',
        boxShadow: '0 0 0 1px hsla(0,0%,0%,0.1),0 4px 11px hsla(0,0%,0%,0.1)',
        marginBottom: '8px',
        marginTop: '8px',
        width: '100% !important',
        boxSizing: 'border-box',
    }),
    menuList: (styles) => ({
        ...styles,
        position: 'inherit !important',
    }),
};
const customStyles = {
    // option: (provided, state) => ({
    //     ...provided,
    //     borderBottom: '1px dotted pink',
    //     color: state.isSelected ? 'red' : 'blue',
    //     padding: 20,
    // }),
    container: () => ({
        // none of react-select's styles are passed to <Control />
        // width: 200,
        zIndex: '99999',

    }),
    menuPortal: () => ({
        // none of react-select's styles are passed to <Control />
        // width: 200,
        zIndex: '2',


    }),
    indicatorSeparator: () => ({
        // none of react-select's styles are passed to <Control />
        // width: 200,
    }),
    IndicatorsContainer: () => ({
        // none of react-select's styles are passed to <Control />
        // width: 200,
        width: '20%',

    }),
    multiValueLabel: () => ({
        // none of react-select's styles are passed to <Control />
        // width: 200,
        overflow: 'visible',

    }),
    singleValue: (provided, state) => {
        const opacity = state.isDisabled ? 0.5 : 1;
        const transition = 'opacity 300ms';

        return {...provided, opacity, transition};
    }
}

class AdvancedFilters extends React.Component {
    promptTextCreator(value) {
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    state = {
        selectedOption: null,
        filters: {
            category: 'sell',
            advertiser: 'all',
        },
        searchAdvancedClick: false,
        priceRangeError: false,
        priceRangeErrorMessage: '',
        filterApplied: undefined,
        selectedCategory: 'sell',
        selectedType: 'apartman',
        category: '',
        type: '',
        regions: [],
        selectedRegions: [{
            value: '',
            label: 'انتخاب کنید ...'
        }],
        selectedAdvertiser: null,
        selectedCity: "qazvin",
        showAdvansedSearch: false,
        showFilter: true,
        allFields: {
            sell: {
                1: ['room', 'min_price', 'max_price', 'plan', 'max_age', 'from_floor', 'to_floor', 'max_unit', 'elevator', 'parking', 'exchange'],
                2: ['min_price', 'max_price', 'exchange'],
                3: ['room', 'min_price', 'max_price', 'plan', 'max_age', 'from_floor', 'to_floor', 'max_unit', 'elevator', 'parking', 'exchange'],
                4: ['min_price', 'max_price', 'plan', 'tarakom', 'exchange'],
                5: ['room', 'min_price', 'max_price', 'plan', 'max_age', 'max_unit', 'parking', 'exchange'],
                6: ['min_price', 'max_price', 'exchange'],
                7: ['min_price', 'max_price', 'exchange'],
            },
            rent: {
                1: ['room', 'max_mortgage', 'min_mortgage', 'max_rent', 'min_rent', 'plan', 'max_age', 'from_floor', 'to_floor', 'max_unit', 'elevator', 'parking'],
                2: ['max_mortgage', 'min_mortgage', 'max_rent', 'min_rent'],
                3: ['room', 'max_mortgage', 'min_mortgage', 'max_rent', 'min_rent', 'plan', 'max_age', 'from_floor', 'to_floor', 'max_unit', 'elevator', 'parking'],
                5: ['room', 'plan', 'max_age', 'max_unit', 'parking'],
                6: ['max_mortgage', 'min_mortgage', 'max_rent', 'min_rent'],
                7: ['max_mortgage', 'min_mortgage', 'max_rent', 'min_rent'],
            },
        },

        fields: ["min_price", "max_price", "plan", "elevator", "parking", "exchange"]

    };

    componentWillReceiveProps(nextProps) {

        const {selectedCity} = nextProps;
        this.setState({selectedCity: selectedCity});
        if (selectedCity !== this.props.selectedCity) {
            this.props.filterEstates(selectedCity);
        }
        this.setState(prevState => ({
            filters: {
                ...prevState.filters,
                region_id: [],
                region_is_selected: []
            },
            selectedRegions: this.state.regions.filter((region) => (region.city_slug === selectedCity))
        }))
    }

// handleMultiSelectChange = (refName, e) => {
//     const region_id = [].filter.call(this.refs[refName].options, o => o.selected).map(o => o.value);
//     this.setState({
//         region_id
//     });
// };
    changeShowAdvansedSearch = () => {
        this.setState({
            showAdvansedSearch: !this.state.showAdvansedSearch,
        })
    };

    componentDidMount() {
        const selectedCity = this.state.selectedCity;
        // this.setState({
        //     selectedRegions: this.state.regions.filter((region) => (region.city_slug === selectedCity))
        // });
        const advancedFilter =JSON.parse( window.localStorage.getItem('advancedFilter'));
        if(advancedFilter){
        const {filters , fields ,showAdvansedSearch}= advancedFilter;
            this.setState({
                filters,
                fields,
                showAdvansedSearch
            })
        }
        axios.get('/api/getRegionsByCitySlug/').then((response) => {
            const originalRegions = response.data;
            let regions = [{label: 'انتخاب کنید', value: ''}];
            originalRegions.map(region => {
                regions.push({
                    label: region.name,
                    value: region.id,
                    city_slug: region.city.slug
                });
            });
            this.setState({
                regions
            }, () => {
                this.setState({
                    selectedRegions: this.state.regions.filter((region) => (region.city_slug === selectedCity))
                });
            })
        }).catch(error => {
            console.log('errerGetRegion: ' + error)
        });
        // axios.get('/api/getRegionsByCitySlug/'+selectedCity).then((response) => {
        //     this.setState({
        //         regions : response.data,
        //     })
        // }).catch(error =>{
        //     console.log('errerGetRegion: '+error)
        // });
        const selectedCitySlug = this.state.selectedCity;
    }

    handleInputChange = (event) => {
        console.log(event.target);
        const target = event.target;
        const value = target.type === 'checkbox' ? target.checked : target.value;

        const name = target.name;
        this.setState(prevState => ({
            filters: {
                ...prevState.filters,
                [name]: value,
            }
        }));
    };

    handelCategoryClick = (category) => {
        const filters = {category};
        window.localStorage.removeItem('advancedFilter');
        const fields = category === 'rent' ? this.state.allFields.rent[1] : this.state.allFields.sell[1];
        this.setState({
            filters,
            showFilter: true,
            showAdvansedSearch: false,
            category,
            fields
        });
        const {selectedCity} = this.state;
        this.props.filterEstates(selectedCity, filters);
        // this.props.history.push(`category=${category}`);
    };

    handleTypeClick = (event) => {
        let fields = [];
        const category = this.state.filters.category;
        const value = event.target.value;
        switch (value) {
            case "1":
                fields = category === 'rent' ? this.state.allFields.rent[1] : this.state.allFields.sell[1];
                break;
            case "2":
                fields = category === 'rent' ? this.state.allFields.rent[2] : this.state.allFields.sell[2];
                break;
            case "3":
                fields = this.state.allFields.sell[3];
                break;
            case "4":
                fields = category === 'rent' ? this.state.allFields.rent[4] : this.state.allFields.sell[4];
                break;
            case "5":
                fields = category === 'rent' ? this.state.allFields.rent[5] : this.state.allFields.sell[5];
                break;
            case "6":
                fields = category === 'rent' ? this.state.allFields.rent[6] : this.state.allFields.sell[6];
                break;
            case "7":
                fields = category === 'rent' ? this.state.allFields.rent[7] : this.state.allFields.sell[7];
                break;
        }
        console.log(value, category, fields);
        this.setState({fields});
        this.handleInputChange(event)
    };

    onFormSubmit = (e) => {
        e.preventDefault();
        this.setState({
            filterApplied: false
        });
        const city = this.props.match.params.city;

        this.props.filterEstates(city, this.state.filters);

        const advancedFilter = {
            filters: this.state.filters,
            fields: this.state.fields,
            showAdvansedSearch : this.state.showAdvansedSearch
        };
        window.localStorage.setItem('advancedFilter', JSON.stringify(advancedFilter));

        setTimeout(() => {
            this.setState({
                filterApplied: true
            })
        }, 2000);
    };

    onFormReset = () => {
        this.setState(() => ({priceRangeError: false, filterApplied: false}));
        this.props.clearFilters();
    };
    handleMultiSelectChange = (name, selectedOption) => {
        const selectedValue = selectedOption.map(option => (option.value));
        const selectedName = name + '_selected';

        this.setState(prevState => ({
            filters: {
                ...prevState.filters,
                [selectedName]: selectedOption,
                [name]: selectedValue,
            }
        }));
    };
    handleSelectChange = (name, selectedOption) => {
        const selectedValue = selectedOption.value;
        const selectedName = name + '_selected';
        this.setState(prevState => ({
            filters: {
                ...prevState.filters,
                [selectedName]: selectedOption,
                [name]: selectedOption.value,
            }
        }));
        console.log(`Option selected:`, selectedOption, 'min_total_price', name);
    };

    render() {
        const {selectedOption , filters} = this.state;

        const {priceRangeError, priceRangeErrorMessage, fields, selectedRegions} = this.state;
        return (
            <div>
                {/*{this.state.filterApplied && <p className={"advanced-filter-applied-text"}>فیلتر اعمال شد</p>}*/}
                <div className="search-slide  nopadding">
                    <ul className="nav nav-tabs pill-index">
                        <li className={`col-lg-4 col-xs-4 nopadding ${(filters.category === 'sell' && this.state.showFilter) ? 'active' : ''}`}
                            onClick={this.handelCategoryClick.bind(this, 'sell')}>
                            <a data-toggle="tab">خرید</a></li>
                        <li className={`col-lg-4 col-xs-4 nopadding ${(filters.category === 'rent' && this.state.showFilter) ? 'active' : ''}`}
                            onClick={this.handelCategoryClick.bind(this, 'rent')}><a data-toggle="tab">اجاره</a></li>
                        <li className={`col-lg-4 col-xs-4 nopadding ${(filters.category === 'presell' && this.state.showFilter) ? 'active' : ''}`}
                            onClick={this.handelCategoryClick.bind(this, 'presell')}><a data-toggle="tab">پیش خرید</a>
                        </li>
                    </ul>

                    {this.state.showFilter && (
                        <div className="tab-content">
                            <div className="tab-pane fade in active">
                                <div className="advance-search">
                                    <form onSubmit={this.onFormSubmit} ref="sellForm"
                                          className="advance-search-form clearfix">
                                        <div className="col-sm-6 col-xs-6 option-bar">
                                            <div className="selectwrap field placeholder">
                                                <label className="active">نوع ملک</label>
                                                <select className="search-select type-state label"
                                                        name="type_id" id="select"
                                                        onChange={this.handleTypeClick.bind(this)}
                                                        value={filters.type_id ? filters.type_id : ''}>
                                                    <option className="light-gray" value="">انتخاب کنید...</option>
                                                    <option className="light-gray" value="1">آپارتمانی</option>
                                                    <option className="light-gray" value="2"> مغازه/تجاری</option>
                                                    <option className="light-gray" value="3">اداری</option>
                                                    {!(filters.category === 'rent') && (
                                                        <option className="light-gray" value="4">زمین/کلنگی</option>
                                                    )}
                                                    <option className="light-gray" value="5">خانه/ویلایی</option>
                                                    <option className="light-gray" value="6">باغ/کشاورزی</option>
                                                    <option className="light-gray" value="7">کارخانه/کارگاه</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div className="col-sm-6 col-xs-6 option-bar">

                                            {this.state.selectedCity === 'qazvin' ?
                                                <div className="selectwrap field placeholder">
                                                    <label className="active">منطقه</label>
                                                    <div className="multiselect-arrow">
                                                        <ReactMultiSelectCheckboxes key={this.state.category}
                                                                                    options={selectedRegions}
                                                                                    value={filters.region_id_selected &&
                                                                                        filters.region_id_selected }
                                                                                    styles={multiSelectCustomStyle}
                                                                                    isMulti={true}
                                                                                    onChange={this.handleMultiSelectChange.bind(this, 'region_id')}
                                                                                    name="region_id[]"
                                                                                    placeholderButtonLabel={<span
                                                                                        className="light-gray-select">انتخاب کنید ...</span>}
                                                                                    hideSearch={true}
                                                                                    isRtl={true}
                                                        />
                                                    </div>
                                                </div>
                                                :
                                                <div className="selectwrap field placeholder">
                                                    <label className="active">منطقه</label>
                                                    <select id="multiselect" disabled={true} name="label"
                                                            className="label multiselect">
                                                        <option className="light-gray" value=""> انتخاب کنید ...
                                                        </option>

                                                    </select>
                                                </div>
                                            }
                                        </div>
                                        {fields.indexOf("min_mortgage") > -1 && (
                                            <div className="col-sm-6 col-xs-6 option-bar">
                                                <div className="selectwrap field placeholder">
                                                    <label className="active"> حداقل رهن(تومان)</label>
                                                    <div className="multiselect-arrow">
                                                        <Creatable name="min_total_price"
                                                                   value={filters.min_total_price_selected ? filters.min_total_price_selected : {
                                                                       value: '',
                                                                       label: 'انتخاب کنید ...'
                                                                   }}
                                                                   options={rent_min_total_prices} styles={customStyles}
                                                                   promptTextCreator={(label) => (label.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","))}
                                                                   onChange={this.handleSelectChange.bind(this, 'min_total_price')}
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        )}
                                        {fields.indexOf("max_mortgage") > -1 && (

                                            <div className="col-sm-6 col-xs-6 option-bar">
                                                <div className="selectwrap field placeholder">
                                                    <label className="active"> حداکثر رهن(تومان)</label>
                                                    <div className="multiselect-arrow">
                                                        <Creatable name="max_total_price"
                                                                   value={filters.max_total_price_selected ? filters.max_total_price_selected : {
                                                                       value: '',
                                                                       label: 'انتخاب کنید ...'
                                                                   }}
                                                                   options={rent_max_total_prices} styles={customStyles}
                                                                   promptTextCreator={(label) => (label.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","))}
                                                                   onChange={this.handleSelectChange.bind(this, 'max_total_price')}
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        )}
                                        {fields.indexOf("min_rent") > -1 && (
                                            <div className="col-sm-6 col-xs-6 option-bar">
                                                <div className="selectwrap field placeholder">
                                                    <label className="active"> حداقل اجاره(تومان)</label>
                                                    <div className="multiselect-arrow">
                                                        <Creatable name="min_price"
                                                                   value={filters.min_price_selected ? filters.min_price_selected : {
                                                                       value: '',
                                                                       label: 'انتخاب کنید ...'
                                                                   }}
                                                                   options={rent_min_prices} styles={customStyles}
                                                                   promptTextCreator={(label) => (label.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","))}
                                                                   onChange={this.handleSelectChange.bind(this, 'min_price')}
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        )}
                                        {fields.indexOf("max_rent") > -1 && (
                                            <div className="col-sm-6 col-xs-6 option-bar">
                                                <div className="selectwrap field placeholder">
                                                    <label className="active"> حداکثر اجاره(تومان)</label>
                                                    <div className="multiselect-arrow">
                                                        <Creatable name="max_price"
                                                                   value={filters.max_price_selected ? filters.max_price_selected : {
                                                                       value: '',
                                                                       label: 'انتخاب کنید ...'
                                                                   }}
                                                                   options={rent_max_prices} styles={customStyles}
                                                                   promptTextCreator={(label) => (label.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","))}
                                                                   onChange={this.handleSelectChange.bind(this, 'max_price')}
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        )}
                                        {fields.indexOf("min_price") > -1 && (
                                            <div className="col-sm-6 col-xs-6 option-bar">
                                                <div className="selectwrap field placeholder">
                                                    <label className="active"> حداقل قیمت (تومان)</label>
                                                    <div className="multiselect-arrow">
                                                        <Creatable name="min_total_price"
                                                                   value={filters.min_total_price_selected ? filters.min_total_price_selected : {
                                                                       value: '',
                                                                       label: 'انتخاب کنید ...'
                                                                   }}
                                                                   options={prices} styles={customStyles}
                                                                   promptTextCreator={(label) => (label.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","))}
                                                                   onChange={this.handleSelectChange.bind(this, 'min_total_price')}
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        )}
                                        {fields.indexOf("max_price") > -1 && (
                                            <div className="col-sm-6 col-xs-6 option-bar">
                                                <div className="selectwrap field placeholder">
                                                    <label className="active">حداکثر قیمت (تومان)</label>
                                                    <div className="multiselect-arrow">
                                                        <Creatable name="max_total_price"
                                                                   value={filters.max_total_price_selected ? filters.max_total_price_selected : {
                                                                       value: '',
                                                                       label: 'انتخاب کنید ...'
                                                                   }}
                                                                   options={total_prices} styles={customStyles}
                                                                   promptTextCreator={(label) => (label.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","))}
                                                                   onChange={this.handleSelectChange.bind(this, 'max_total_price')}
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        )}
                                        <div className="col-sm-6 col-xs-6 option-bar">
                                            <div className="selectwrap field placeholder">
                                                <label className="active"> حداقل متراژ (متر)</label>
                                                <div className="multiselect-arrow">
                                                    <Creatable name="min_area"
                                                               value={filters.min_area_selected ? filters.min_area_selected : {
                                                                   value: '',
                                                                   label: 'انتخاب کنید ...'
                                                               }}
                                                               options={min_areas} styles={customStyles}
                                                               promptTextCreator={(label) => (label.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","))}
                                                               onChange={this.handleSelectChange.bind(this, 'min_area')}
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div className="col-sm-6 col-xs-6 option-bar">
                                            <div className="selectwrap field placeholder">
                                                <label className="active"> حداکثر متراژ (متر)</label>
                                                <div className="multiselect-arrow">
                                                    <Creatable name="max_area"
                                                               value={filters.max_area_selected ? filters.max_area_selected : {
                                                                   value: '',
                                                                   label: 'انتخاب کنید ...'
                                                               }}
                                                               options={max_areas} styles={customStyles}
                                                               promptTextCreator={(label) => (label.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","))}
                                                               onChange={this.handleSelectChange.bind(this, 'max_area')}
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        {fields.indexOf("room") > -1 && (
                                            <div className="col-sm-6 col-xs-6 option-bar room">
                                                <div className="selectwrap field placeholder">
                                                    <label className="active">تعداد اتاق</label>
                                                    <select name="room" onChange={this.handleInputChange}
                                                            value={filters.room ? filters.room : ''}
                                                            className="search-select label">
                                                        <option className="light-gray" value=""> مهم نیست</option>
                                                        <option className="light-gray" value="1">1</option>
                                                        <option className="light-gray" value="2">2</option>
                                                        <option className="light-gray" value="3">3</option>
                                                        <option className="light-gray" value="4">4</option>
                                                        <option className="light-gray" value="5">5 و بیشتر</option>
                                                    </select>
                                                </div>
                                            </div>
                                        )}

                                        {this.state.showAdvansedSearch && <div>
                                            {fields.indexOf("plan") > -1 && (
                                                <div className="col-sm-6 col-xs-6 option-bar location">
                                                    <div className="selectwrap field placeholder">
                                                        <label className="active"> موقیعت</label>
                                                        <div className="multiselect-arrow">
                                                            <ReactMultiSelectCheckboxes key={this.state.category}
                                                                                        styles={multiSelectCustomStyle}
                                                                                        value={filters.plan_id_selected &&
                                                                                            filters.plan_id_selected }
                                                                                        isMulti={true}
                                                                                        onChange={this.handleMultiSelectChange.bind(this, 'plan_id')}
                                                                                        options={plans}
                                                                                        name="plan_id[]"
                                                                                        hideSearch={true}
                                                                                        isRtl={true}
                                                                                        placeholderButtonLabel={<span
                                                                                            className="light-gray-select">انتخاب کنید ...</span>}
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                            )}
                                            {(fields.indexOf("max_age") > -1 && filters.category !== 'presell') && (
                                                <div className="col-sm-6 col-xs-6 option-bar age">
                                                    <div className="selectwrap field placeholder">
                                                        <label className="active"> حداکثر عمر بنا(سال)</label>
                                                        <select name="max_age" onChange={this.handleInputChange}
                                                                className="search-select label">
                                                            <option className="light-gray" value=""> مهم نیست</option>
                                                            <option className="light-gray" value="0"> نوساز</option>
                                                            <option className="light-gray" value="1"> 1 سال</option>
                                                            <option className="light-gray" value="2"> 2 سال</option>
                                                            <option className="light-gray" value="4"> 4 سال</option>
                                                            <option className="light-gray" value="6"> 6 سال</option>
                                                            <option className="light-gray" value="8"> 8 سال</option>
                                                            <option className="light-gray" value="10"> 10 سال</option>
                                                            <option className="light-gray" value="12"> 12 سال</option>
                                                            <option className="light-gray" value="14"> 14 سال</option>
                                                            <option className="light-gray" value="16"> 16 سال</option>
                                                            <option className="light-gray" value="18"> 18 سال</option>
                                                            <option className="light-gray" value="1000"> 20 و بیشتر
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            )}
                                            {fields.indexOf("max_unit") > -1 && (

                                                <div className="col-sm-6 col-xs-6 option-bar unit">
                                                    <div className="selectwrap field placeholder">
                                                        <label className="active"> حداکثر واحد</label>
                                                        <select name="max_unit" onChange={this.handleInputChange}
                                                                className="search-select label">
                                                            <option className="light-gray" value=""> مهم نیست</option>
                                                            <option className="light-gray" value="1"> 1 واحد</option>
                                                            <option className="light-gray" value="2">2واحد</option>
                                                            <option className="light-gray" value="3"> 3واحد</option>
                                                            <option className="light-gray" value="4">4 واحد</option>
                                                            <option className="light-gray" value="5">5 واحد</option>
                                                            <option className="light-gray" value="6">6 واحد</option>
                                                            <option className="light-gray" value="7">7 واحد</option>
                                                            <option className="light-gray" value="8">8 واحد</option>
                                                            <option className="light-gray" value="9">9 واحد</option>
                                                            <option className="light-gray" value="10">10 واحد</option>
                                                            <option className="light-gray" value="11">11 واحد</option>
                                                            <option className="light-gray" value="12">12 واحد</option>
                                                            <option className="light-gray" value="13">13 واحد</option>
                                                            <option className="light-gray" value="14">14 واحد</option>
                                                            <option className="light-gray" value="15">15 واحد</option>
                                                            <option className="light-gray" value="1000">16 و بیشتر
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            )}
                                            {fields.indexOf("from_floor") > -1 && (
                                                <div className="col-sm-6 col-xs-6 option-bar floor">
                                                    <div className="selectwrap field placeholder">
                                                        <label className="active"> از طبقه</label>
                                                        <select name="from_floor" onChange={this.handleInputChange}
                                                                ref="sellMinFloor" className="search-select label">
                                                            <option className="light-gray" value=""> مهم نیست</option>
                                                            <option className="light-gray" value="-1"> زیر زمین</option>
                                                            <option className="light-gray" value="0">هم کف</option>
                                                            <option className="light-gray" value="1"> طبقه اول</option>
                                                            <option className="light-gray" value="2"> طبقه دوم</option>
                                                            <option className="light-gray" value="3"> طبقه سوم</option>
                                                            <option className="light-gray" value="4"> طبقه چهارم
                                                            </option>
                                                            <option className="light-gray" value="5"> طبقه پنجم</option>
                                                            <option className="light-gray" value="6"> ششم و بیشتر
                                                            </option>

                                                        </select>
                                                    </div>
                                                </div>
                                            )}
                                            {fields.indexOf("to_floor") > -1 && (
                                                <div className="col-sm-6 col-xs-6 option-bar floor">
                                                    <div className="selectwrap field placeholder">
                                                        <label className="active"> تا طبقه</label>
                                                        <select name="to_floor" onChange={this.handleInputChange}
                                                                className="search-select label">
                                                            <option className="light-gray" value=""> مهم نیست</option>
                                                            <option className="light-gray" value="-1"> زیر زمین</option>
                                                            <option className="light-gray" value="0">هم کف</option>
                                                            <option className="light-gray" value="1"> طبقه اول</option>
                                                            <option className="light-gray" value="2"> طبقه دوم</option>
                                                            <option className="light-gray" value="3"> طبقه سوم</option>
                                                            <option className="light-gray" value="4"> طبقه چهارم
                                                            </option>
                                                            <option className="light-gray" value="5"> طبقه پنجم</option>
                                                            <option className="light-gray" value="50"> ششم و بیشتر
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            )}
                                            {fields.indexOf("tarakom") > -1 && (
                                                <div className="col-sm-6 col-xs-6 option-bar tarakom">
                                                    <div className="selectwrap field placeholder">
                                                        <label className="active"> تراکم</label>
                                                        <select name="tarakom" onChange={this.handleInputChange}
                                                                className="search-select label">
                                                            <option className="light-gray" value="">مهم نیست</option>
                                                            <option className="light-gray" value="1">1</option>
                                                            <option className="light-gray" value="2">2</option>
                                                            <option className="light-gray" value="3">3</option>
                                                            <option className="light-gray" value="4">4</option>
                                                            <option className="light-gray" value="5">5</option>
                                                            <option className="light-gray" value="6">6 به بالا</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            )}
                                            <div className="clearfix"></div>

                                            <div className="option-bar-morefilter col-lg-12 col-sm-12 nopadding">

                                                <div className=" col-xs-6 image-state">
                                                    <label htmlFor="photo">
                                                        <input id="photo" name="photo" onChange={this.handleInputChange}
                                                               type="checkbox" value="photo" checked={filters.photo && true} /> ملک عکسدار
                                                    </label>

                                                </div>
                                                {fields.indexOf("elevator") > -1 && (

                                                    <div className=" col-xs-6 elevator">
                                                        <label htmlFor="elevator">

                                                            <input id="elevator" name="elevator"
                                                                   onChange={this.handleInputChange}
                                                                   type="checkbox" value="elevator" checked={filters.elevator && true}/> آسانسوردار
                                                        </label>
                                                    </div>
                                                )}
                                                {fields.indexOf("parking") > -1 && (
                                                    <div className="col-xs-6 parking">
                                                        <label htmlFor="parking">

                                                            <input name="parking" id="parking"
                                                                   onChange={this.handleInputChange}
                                                                   type="checkbox" value="parking" checked={filters.parking && true}/> پارکینگ
                                                            دار
                                                        </label>
                                                    </div>
                                                )}
                                                {fields.indexOf("exchange") > -1 && (
                                                    <div className=" col-xs-6 exchange">
                                                        <label htmlFor="exchange">
                                                            <input id="exchange" name="exchange"
                                                                   onChange={this.handleInputChange}
                                                                   type="checkbox" value="exchange" checked={filters.exchange && true}/> معاوضه
                                                        </label>

                                                    </div>
                                                )}
                                            </div>

                                            <div className="type-adver col-lg-12 col-sm-12">
                                                <div>نوع آگهی دهنده:</div>
                                                <label id="all">
                                                    <input id="all" type="radio" ref="advertiser" name="advertiser"
                                                           value="all"
                                                           checked={!filters.advertiser || filters.advertiser === 'all'}
                                                           onChange={this.handleInputChange}/>فرقی نمیکند
                                                </label><br/>
                                                <label id="admin">
                                                    <input id="admin" type="radio" ref="advertiser" name="advertiser"
                                                           value="admin"
                                                           checked={filters.advertiser === 'admin'}
                                                           onChange={this.handleInputChange}/> املاک 79
                                                </label><br/>
                                                <label id="user">
                                                    <input id="user" type="radio" ref="advertiser" name="advertiser"
                                                           value="owner"
                                                           checked={filters.advertiser === 'owner'}
                                                           onChange={this.handleInputChange}/> شخصی
                                                </label><br/>
                                                <label id="agent">
                                                    <input id="agent" type="radio" ref="advertiser" name="advertiser"
                                                           value="agent"
                                                           checked={filters.advertiser === 'agent'}
                                                           onChange={this.handleInputChange}/> سایر املاک
                                                </label><br/>
                                            </div>


                                        </div>}
                                        <div className="clearfix"></div>
                                        <div className="panel-heading col-lg-12 col-xs-6">
                                            <h4 className="panel-title">

                                                <div
                                                    className={`${this.state.showAdvansedSearch ? 'active arrow-down' : 'arrow-down'}`}>
                                                    <a
                                                        data-toggle="collapse" data-parent="#accordion"
                                                        onClick={this.changeShowAdvansedSearch}>فیلتر
                                                        بیشتر</a></div>
                                            </h4>
                                        </div>

                                        <div className="option-bar col-lg-3 col-sm-3 col-xs-6 search-button ">
                                            <input ref="sellSubmit" type="submit" value="جستجو"
                                                   className={`real-btn btn ${this.state.filterApplied === false ? 'disabled' : ''}`}/>
                                        </div>

                                    </form>
                                </div>

                            </div>
                        </div>
                    )}

                </div>
                {/*<Form onSubmit={this.onFormSubmit}>*/}
                {/*<div className={"text-center margin-below"}>*/}
                {/*<p>Ratings: </p>*/}
                {/*<FormControl componentClass="select" placeholder="All" name={"ratings"}>*/}
                {/*<option value={ANY}>{ANY}</option>*/}
                {/*<option value={MORE_THAN_FOUR}>{MORE_THAN_FOUR}</option>*/}
                {/*<option value={MORE_THAN_THREE}>{MORE_THAN_THREE}</option>*/}
                {/*<option value={ONE_TO_THREE}>{ONE_TO_THREE}</option>*/}
                {/*</FormControl>*/}
                {/*</div>*/}

                {/*<div className={"text-center margin-below"}>*/}
                {/*<p>Price Range: </p>*/}
                {/*<div className={"inline-advanced-div"}>*/}
                {/*<FormGroup controlId="formInlineFrom">*/}
                {/*<FormControl type="number" placeholder="From" className={"left-advanced-filter"} name={"from"}/>*/}
                {/*</FormGroup>{' '}*/}
                {/*<FormGroup controlId="formInlineTo">*/}
                {/*<FormControl type="number" placeholder="To" className={"right-advanced-filter"} name={"to"}/>*/}
                {/*</FormGroup>*/}
                {/*</div>*/}
                {/*{priceRangeError && <span className={"error-message"}>{priceRangeErrorMessage}</span>}*/}
                {/*</div>*/}

                {/*<div className={"text-center margin-below"}>*/}
                {/*<p>Fast Shipping: </p>*/}
                {/*<FormControl componentClass="select" placeholder="All" name={"fast_shipping"}>*/}
                {/*<option value={ANY}>{ANY}</option>*/}
                {/*<option value={YES}>{YES}</option>*/}
                {/*<option value={NO}>{NO}</option>*/}
                {/*</FormControl>*/}
                {/*</div>*/}

                {/*<div className={"inline-advanced-div"}>*/}
                {/*<Button className={"btn-sm left-advanced-filter"} type={"submit"}>Apply filters</Button>*/}
                {/*<Button className={"btn-sm right-advanced-filter"} type={"reset"} onClick={this.onFormReset}>Clear filters</Button>*/}
                {/*</div>*/}
                {/*</Form>*/}
            </div>
        )
    }
}

const mapStateToProps = ({selectedCity}) => {
    return {
        selectedCity
    };
}
export default withRouter(connect(mapStateToProps, {filterEstates})(AdvancedFilters));
