import _ from 'lodash';
import React, {Component} from 'react'
import axios from 'axios';
import {ACCESS_TOKEN, REFRESH_TOKEN} from "../../api/strings";
import {changeSelectedCity, changeDisabledCity} from "../../actions/city";
import {logoutAPI} from "../../api/apiURLs";
import {connect} from "react-redux";
import ReactMultiSelectCheckboxes from 'react-multiselect-checkboxes';
import * as ReactDOM from "react-dom";
import {Link} from "react-router-dom";

const style = {
    margin: '0 auto',
    width: '90%'
};

class WizardForm extends Component {
    constructor(props) {
        super(props);
        this.minPrice = React.createRef();
        this.myRef = React.createRef();
        this.textName = React.createRef();
        this.selectRegion = React.createRef();
        this.email = React.createRef();

        this.nextPage = this.nextPage.bind(this);
        this.previousPage = this.previousPage.bind(this);
        this.state = {
            estateInfo: {
                city_id: null,
                type_id: null,
            },
            cities: {
                1: {id: 1, province_id: 18, name: "قزوین", slug: "qazvin"},
                2: {id: 2, province_id: 18, name: "آبیک", slug: "abyek"},
                3: {id: 3, province_id: 18, name: "تاکستان", slug: "takestan"},
                4: {id: 4, province_id: 18, name: "الوند", slug: "alvand"},
                5: {id: 5, province_id: 18, name: "محمدیه", slug: "mohammadieh"},
                6: {id: 6, province_id: 18, name: "مهرگان", slug: "mehregan"},
                7: {id: 7, province_id: 18, name: "الموت", slug: "alamut"},
                8: {id: 8, province_id: 18, name: "شریفیه", slug: "sharifiye"},
                9: {id: 9, province_id: 18, name: "کورانه", slug: "kouraneh"},
                10: {id: 10, province_id: 18, name: "آبگرم", slug: "abgarm"},
                11: {id: 11, province_id: 18, name: "رشتقون", slug: "Rashteghoun"},
                12: {id: 12, province_id: 18, name: "بیدستان", slug: "bidestan"}
            },
            owner: null,
            page: 1,
            category: 'sell',
            types: {
                1: {id: 1, name: "آپارتمان"},
                2: {id: 2, name: "مغازه/تجاری"},
                3: {id: 3, name: "اداری"},
                4: {id: 4, name: "زمین/کلنگی"},
                5: {id: 5, name: "خانه/ویلا"},
                6: {id: 6, name: "باغ و کشاورزی"},
                7: {id: 7, name: "کارخانه/کارگاه"},
            },
            plans: [
                {id: 1, name: "شمالی", created_at: "2018-11-29 04:22:12", updated_at: "2018-11-29 04:22:12"},
                {id: 2, name: "جنوبی", created_at: "2018-11-29 04:22:12", updated_at: "2018-11-29 04:22:12"},
                {id: 3, name: "شرقی-غربی", created_at: "2018-11-29 04:22:12", updated_at: "2018-11-29 04:22:12"},
                {id: 4, name: "دوکله", created_at: "2018-11-29 04:22:12", updated_at: "2018-11-29 04:22:12"},
                {id: 5, name: "دو نبش", created_at: "2018-11-29 04:22:12", updated_at: "2018-11-29 04:22:12"},
                {id: 6, name: "سه نبش", created_at: "2018-11-29 04:22:12", updated_at: "2018-11-29 04:22:12"}
            ],
            fields: {},
            regions: [],
            loan: false,
            exchange_with: false,
            cabinet: false,
            delivery: false,
            owner_name: null,
            checkedFacilities: new Map(),
            facilities: [],
            isAuthenticated: false,
            isAdmin: false,
            errors: {},
        }
    }


    componentWillReceiveProps(nextProps) {
        const {selectedCity} = nextProps;
    }

    componentDidMount() {
        this.props.changeDisabledCity(true);
        const access_token = window.localStorage.getItem(ACCESS_TOKEN);
        // if(this.props.authentication.isAuthenticated && access_token !== null) {
        if (access_token !== null) {
            const headers = {
                Accept: "application/json",
                Authorization: `Bearer ${access_token}`
            };
            axios.get('/api/getUserInformation', {headers: {...headers}})
                .then(response => {
                    const {name, email, phone, role} = response.data;

                    const isAdmin = (role === "expert" || role === "super_admin" || role === "admin") ? true : false;
                    this.setState(prevState => ({
                        estateInfo: {
                            ...prevState.estateInfo,
                            name,
                            email,
                            phone
                        },
                        isAuthenticated: true,
                        isAdmin
                    }));
                });
        }
    }

    handleMultiSelectChange = (selectedOption) => {
        const selectedValue = selectedOption.map(option => (option.value));
        this.setState(prevState => ({
            estateInfo: {
                ...prevState.estateInfo,
                region: selectedValue
            }
        }));
    };
    changeAdvertismentInfo = (name, value) => {
        this.setState(prevState => ({
            estateInfo: {
                ...prevState.estateInfo,
                [name]: value
            }
        }));
    };
    handleEstateInputChange = (event) => {
        const target = event.target;
        const value = target.type === 'checkbox' ? target.checked : target.value.replace(/(,| )/g, '');
        const name = target.name;
        this.setState(prevState => ({
            estateInfo: {
                ...prevState.estateInfo,
                [name]: value
            }
        }));
    };


    nextPage = () => {
        this.setState({page: this.state.page + 1})

    };

    previousPage = () => {
        this.setState({page: this.state.page - 1})
    };

    changeCity = (city) => {
        console.log('cityId:', city.cityId);
        this.changeAdvertismentInfo('city_id', city.cityId);

        this.props.changeSelectedCity(city.citySlug);
        const url = `/api/getRegions/${city.cityId}`;
        axios.get(url).then((response) => {
            const originalRegions = response.data;
            console.log('originalRegions', response);
            let regions = [];
            let i;
            for (i = 0; i < originalRegions.length; i++) {
                regions.push({label: originalRegions[i]['name'], value: originalRegions[i]['id']});
            }
            console.log('regions', regions);
            this.setState({
                regions
            })
        }).catch(error => {
            console.log(error)
        });
        this.nextPage();
    };
    changeType = (typeId) => {
        this.changeAdvertismentInfo('type_id', typeId);
        const category = this.state.category;
        const params = {
            type_id: typeId,
            category: category
        }
        axios.get('/api/getFields/', {params}).then((response) => {
            this.setState({
                fields: _.mapKeys(response.data, 'name'),

            })
            console.log('response.data.facility:', response.data.facility);
            if (this.state.fields.facility) {
                axios.get('/api/getFacilities').then((response) => {
                    this.setState({
                        facilities: response.data,
                    })
                }).catch(error => {
                    console.log(error)
                });
            }
        }).catch(error => {
            console.log(error)
        });

        this.nextPage();

    };

    handleSubmitForm = (e) => {
        e.preventDefault();
        // const selectedRegionId = [].filter.call(this.refs.region.options, o => o.selected).map(o => o.value);
        // const selectedPlanId = [].filter.call(this.refs.plan.options, o => o.selected).map(o => o.value);
        let estate = this.state.estateInfo;
        const region = estate.region;
        if (region === undefined || region.length == 0) {
            this.setState(prevState => ({
                errors: {
                    ...prevState.errors,
                    region: ['فیلد منطقه الزامی است']
                }

            }), window.scrollTo(0, 470));


            return;
        }
        estate = {
            ...estate,
        };
        // const access_token = window.localStorage.getItem(ACCESS_TOKEN);
        // if(access_token !== null){
        // let headers;
        // if (this.props.authentication.isAuthenticated && access_token !== null) {
        // if (access_token !== null) {
        //     headers = {
        //         Accept: "application/json",
        //         Authorization: `Bearer ${access_token}`
        //     };
        // }

        // axios.post('/api/estates/darkhast/store_sell', estate, {headers: {...headers}})
        axios.post('/api/estates/darkhast/store_sell', estate)
            .then((response) => {
                // const {token} = response.data;
                // console.log('token', token);
                // if (token) {
                //     window.localStorage.setItem(ACCESS_TOKEN, response.data.token);
                //     window.localStorage.setItem(REFRESH_TOKEN, response.data.token);
                // }
                this.props.changeDisabledCity(false);
                this.setState({page: 4}
                    , () => window.scrollTo(0, 0));
                // this.props.history.push('/darkhast/list');
            }).catch(error => {
            console.log(error);
            const {errors} = error.response.data;
            // const {errors} = error;
            this.setState({
                    errors
                }, () => this.handleSubmitFail()
            )
        });
    };
    handleSubmitFail = () => {
        document.getElementsByClassName('has-error')[0].focus();
    }

    render() {
        const {category, regions, estateInfo, owner, types, page, cities, fields, plans, delivery, errors} = this.state;

        return (
            <section id="content">
                <div id="msform">
                    <div id="progressbar">
                        <div className="logo-breadcrumb"><img src="/asset/images/registerhome-icon.png" alt=""/></div>
                        <span
                            className="text-breadcrumb">  درخواست خرید {estateInfo.type_id ? (types[estateInfo.type_id].name) : 'ملک'} {estateInfo.city_id && (
                            <span> در <i style={{color: 'red'}}> {cities[estateInfo.city_id].name} </i></span>)} </span>
                    </div>
                    {page === 1 && (
                        <fieldset className="register-city">
                            <h2 className="fs-title">یکی از شهرهای زیر را انتخاب کنید: </h2>
                            {_.map(cities, city => (
                                <button onClick={this.changeCity.bind(this, {cityId: city.id, citySlug: city.slug})}
                                        data-slug={city.slug} key={city.slug} type="button"
                                        className="next action-button">{city.name}</button>
                            ))}
                        </fieldset>
                    )}
                    {page === 2 && (
                        <fieldset className="register-state">
                            <h2 className="fs-title">یکی از گزینه های زیر را انتخاب کنید: </h2>
                            {_.map(types, type => (
                                <button onClick={this.changeType.bind(this, type.id)} type="button" key={type.id}
                                        className="next action-button">{type.name}</button>
                            ))}

                            <button onClick={this.previousPage} type="button" name="previous"
                                    className="previous action-button-previous"
                                    value="بازگشت">بازگشت
                            </button>
                            <i className="fa fa-arrow-left"></i>
                        </fieldset>
                    )}
                    {page === 3 && (
                        <fieldset className="register-form">
                            <div className="alert alert-info ">
                                <p>
                                    با تکمیل اطلاعات ملک درخواستی از طریق پیامک و ایمیل زودتر از دیگران از آگهی جدید
                                    باخبر شوید.
                                </p>

                            </div>
                            <form id="register-form" onSubmit={this.handleSubmitForm} method="POST">

                                <div className="info-person">
                                    <h2 className="ff-title">اطلاعات آگهی دهنده</h2>
                                    <div className="show-person">
                                        <div className="col-sm-3 col-xs-12 mb20">
                                            <div className="field placeholder">
                                                <label htmlFor="LastName" className="active"> نام خانوادگی</label>

                                                <input id="LastName" maxLength="50" placeholder=""
                                                       name="name" type="text"
                                                       className={errors.name ? 'has-error' : ''}
                                                       onChange={this.handleEstateInputChange} value={estateInfo.name}
                                                       readOnly={this.state.isAuthenticated && !this.state.isAdmin}
                                                       data-rule-required="true"
                                                       data-msg-required="این فیلد الزامی است"
                                                />
                                                {errors.name && (
                                                    <span className="error">{errors.name}</span>
                                                )}
                                            </div>
                                        </div>
                                    </div>

                                    <div className="col-sm-3 col-xs-12 mb20">
                                        <div className="field placeholder">
                                            <label htmlFor="phone" className="active">تلفن همراه</label>
                                            <input id="phone" name="phone" min="0" type="number" ref='phone'
                                                   data-category="quantity" className={errors.phone ? 'has-error' : ''}
                                                   value={estateInfo.phone} onChange={this.handleEstateInputChange}
                                                   readOnly={this.state.isAuthenticated && !this.state.isAdmin}
                                            />
                                            {errors.phone && (
                                                <span className="error">{errors.phone}</span>
                                            )}

                                        </div>
                                    </div>


                                    <div className="col-sm-6 col-xs-12 mb20">
                                        <div className="field placeholder" ref={this.email}>
                                            <label htmlFor="email" className="active">ایمیل</label>
                                            <input id="email" name="email" ref="email" type="email"
                                                   placeholder="ملکهای مشابه به ایمیل شما ارسال میشود."
                                                   value={estateInfo.email} onChange={this.handleEstateInputChange}
                                                   data-rule-required="true"
                                                   className={errors.email ? 'has-error' : ''}
                                                   data-msg-required="این فیلد الزامی است"
                                                   email="true"
                                                   data-msg-email="آدرس ایمیل صحیح نیست"/>
                                            {errors.email && (
                                                <span className="error">{errors.email}</span>
                                            )}
                                        </div>
                                    </div>
                                </div>
                                <div ref={(ref) => this.myRef = ref}></div>
                                <div className="info-state">
                                    <h2 className="ff-title"> مشخصات اصلی ملک</h2>
                                    <div className="container-fluid pr0">

                                        <div className="col-sm-3 col-xs-6 option-bar">
                                            <div className="selectwrap field placeholder" ref="selectRegion">
                                                <label className="active">منطقه</label>
                                                <div className="multiselect-arrow">
                                                    <ReactMultiSelectCheckboxes options={regions}
                                                                                isMulti={true}
                                                                                onChange={this.handleMultiSelectChange}
                                                                                name="region"
                                                                                placeholderButtonLabel={<span
                                                                                    className="light-gray-select">انتخاب کنید ...</span>}
                                                                                hideSearch={true}
                                                                                isRtl={true}
                                                    />
                                                </div>
                                                {errors.region && (
                                                    <span className="error"
                                                          style={{marginTop: '-6px'}}>{errors.region}</span>
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                    <div className="clearfix" style={{marginBottom: '37px'}}></div>

                                    <div className="col-sm-3 col-xs-6 mb20">
                                        <div className="field placeholder">
                                            <label htmlFor="min_price" className="active"> حداقل قیمت (تومان)</label>
                                            <input id="min_price" className={errors.min_price ? 'has-error' : ''}
                                                   name="min_price" type="text" ref={this.minPrice}
                                                   value={estateInfo.min_price ? estateInfo.min_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : ''}
                                                   onChange={this.handleEstateInputChange}
                                                   placeholder=""/>
                                            {errors.min_price && (
                                                <span className="error">{errors.min_price}</span>
                                            )}
                                        </div>
                                    </div>

                                    <div className="col-sm-3 col-xs-6 mb20">
                                        <div className="field placeholder">
                                            <label htmlFor="max_price" className="active">حداکثر قیمت (تومان)</label>
                                            <input id="max_price" className={errors.max_price ? 'has-error' : ''}
                                                   name="max_price" type="text"
                                                   value={estateInfo.max_price ? estateInfo.max_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : ''}
                                                   onChange={this.handleEstateInputChange}
                                                   placeholder=""
                                            />
                                            {errors.max_price && (
                                                <span className="error">{errors.max_price}</span>
                                            )}
                                        </div>
                                    </div>

                                    <div className="col-sm-3 col-xs-6 mb20">
                                        <div className="field placeholder">
                                            <label htmlFor="min_area" className="active">حداقل متراژ (متر مربع)</label>
                                            <input id="min_area" className={errors.min_area ? 'has-error' : ''}
                                                   name="min_area" type="text"
                                                   value={estateInfo.min_area ? estateInfo.min_area.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : ''}
                                                   onChange={this.handleEstateInputChange}
                                                   placeholder=""
                                            />
                                            {errors.min_area && (
                                                <span className="error">{errors.min_area}</span>
                                            )}

                                        </div>
                                    </div>
                                    <div className="col-sm-3 col-xs-6 mb20">
                                        <div className="field placeholder">
                                            <label htmlFor="max_area" className="active">حداکثر متراژ (متر مربع)</label>
                                            <input id="max_area" className={errors.max_area ? 'has-error' : ''}
                                                   name="max_area" type="text"
                                                   value={estateInfo.max_area ? estateInfo.max_area.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : ''}
                                                   onChange={this.handleEstateInputChange}
                                            />
                                            {errors.max_area && (
                                                <span className="error">{errors.max_area}</span>
                                            )}
                                        </div>
                                    </div>
                                    <div className="clearfix"></div>

                                    {estateInfo.type_id && (types[estateInfo.type_id].name === 'آپارتمان' || types[estateInfo.type_id].name === 'اداری' || types[estateInfo.type_id].name === 'خانه/ویلا') && (
                                        <div className="col-sm-3 col-xs-6 mb20">
                                            <div className="field placeholder">
                                                <label htmlFor="room" className="active">حداقل تعداد اتاق</label>
                                                <input id="room" name="room" type="text"
                                                       className={errors.room ? 'has-error' : ''}
                                                       value={estateInfo.room} onChange={this.handleEstateInputChange}
                                                       placeholder="مهم نیست"
                                                />
                                                {errors.room && (
                                                    <span className="error">{errors.room}</span>
                                                )}
                                            </div>
                                        </div>
                                    )}
                                    {estateInfo.type_id && (types[estateInfo.type_id].name === 'آپارتمان' || types[estateInfo.type_id].name === 'اداری') && (
                                        <div>
                                            <div className="col-sm-3 col-xs-12 checkbox pr0 mt15 ">
                                                <label className="register-facility">
                                                    <input type="checkbox" name="elevator"
                                                           checked={estateInfo.elevator}
                                                           onChange={this.handleEstateInputChange}
                                                    />
                                                    حتما آسانسور دار باشد
                                                </label>

                                            </div>

                                            <div className="col-sm-3 col-xs-12 checkbox pr0 mt15">
                                                <label className="register-facility">
                                                    <input type="checkbox" name="parking"
                                                           checked={estateInfo.parking}
                                                           onChange={this.handleEstateInputChange}
                                                    />
                                                    حتما پارکینگ دار باشد
                                                </label>
                                            </div>
                                        </div>

                                    )}

                                    <div className="clearfix"></div>
                                    <button onClick={this.previousPage} type="button"
                                            className="previous action-button-previous"
                                    >بازگشت
                                    </button>
                                    <button type="submit" id="submit-form" name="submit" className="action-button"
                                            value="Submit"> ثبت درخواست
                                    </button>
                                </div>
                            </form>
                        </fieldset>
                    )}
                    {page === 4 && (

                        <div id={"exTab"} className=" col-xs-no-padding"
                             style={style}>
                            <div className="my-message ">
                                <p className="ui" style={{float: 'right', width: '100%'}}>
                                    درخواست شما ثبت و به مدت یکماه ملکهای جدید از طریق پیامک به اطلاع شما خواهید رسید.
                                </p>
                                <Link to={"/"}><span className={'btn btn-default'}>بازگشت به صفحه اصلی</span></Link>
                            </div>
                        </div>
                    )}
                </div>
            </section>
        )
    }
}

export default connect(null, {changeSelectedCity, changeDisabledCity})(WizardForm)

