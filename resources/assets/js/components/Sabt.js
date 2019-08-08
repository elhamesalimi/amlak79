import _ from 'lodash';
import React, {Component} from 'react'
import axios from 'axios';
import {ACCESS_TOKEN} from "../api/strings";
import {changeSelectedCity, changeDisabledCity} from "../actions/city";
import {connect} from "react-redux";
import ImageDropzone from "./ImageDropzone";
import LoadingScreen from "./LoadingScreen";
import ReactMultiSelectCheckboxes from 'react-multiselect-checkboxes';

const floors = [
    {value: -1, label: 'زیرزمین'},
    {value: 0, label: 'همکف'},
    {value: 1, label: 'طبقه اول'},
    {value: 2, label: 'طبقه دوم'},
    {value: 3, label: 'طبقه سوم'},
    {value: 4, label: 'طبقه چهارم'},
    {value: 5, label: 'طبقه پنجم'},
    {value: 6, label: 'طبقه ششم'},
    {value: 7, label: 'طبقه هفتم'},
    {value: 8, label: 'طبقه هشتم'},
    {value: 9, label: 'طبقه نهم'},
    {value: 10, label: 'طبقه دهم'},
    {value: 11, label: 'طبقه یازدهم'},
    {value: 12, label: 'طبقه دوازدهم'},
    {value: 13, label: 'طبقه سیزدهم'},
    {value: 14, label: 'طبقه چهاردهم'},
    {value: 15, label: 'طبقه پانزدهم'},
    {value: 16, label: 'طبقه شانزدهم'},
    {value: 17, label: 'طبقه هفدهم'},
    {value: 18, label: 'طبقه هجدهم'},
    {value: 19, label: 'طبقه نوزدهم'},
    {value: 20, label: 'طبقه بیستم'},
    {value: 50, label: 'بالاتر از بیست'},
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

class WizardForm extends Component {
    constructor(props) {
        super(props);
        this.nextPage = this.nextPage.bind(this);
        this.previousPage = this.previousPage.bind(this);
        this.state = {
            estateInfo: {
                city_id: null,
                type_id: null,
                category: null,
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
            category: null,
            types: {
                1: {id: 1, name: "آپارتمان"},
                2: {id: 2, name: "مغازه/تجاری"},
                3: {id: 3, name: "اداری"},
                4: {id: 4, name: "زمین/کلنگی"},
                5: {id: 5, name: " خانه/ویلایی"},
                6: {id: 6, name: "باغ و کشاورزی"},
                7: {id: 7, name: "کارخانه/کارگاه"},
            },
            plans: [
                {id: 1, name: "شمالی"},
                {id: 2, name: "جنوبی"},
                {id: 3, name: "شرقی-غربی"},
                {id: 4, name: "دوکله"},
                {id: 5, name: "دو نبش"},
                {id: 6, name: "سه نبش"},
            ],
            fields: {},
            regions: [],
            name: null,
            owner_name: null,
            phone: null,
            telephone: null,
            email: null,
            checkedFacilities: new Map(),
            facilities: [],
            isAuthenticated: false,
            isAdmin: false,
            errors: {},
            files: [],
            isLoading: false,
        }
    }

    componentWillReceiveProps(nextProps) {
        let newCategory = nextProps.match.params.category;
        let prevCategory = this.props.match.params.category;
        const type_id = this.state.estateInfo.type_id;
        if ((prevCategory !== newCategory)) {
            console.log(newCategory)
            this.setState(prevState => ({
                category: newCategory,
                estateInfo: {
                    ...prevState.estateInfo,
                    category: newCategory
                }
            }));
            const params = {
                type_id,
                category: newCategory
            };
            this.getFields(params);
        }
    };

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
                    const {name, email, phone, telephone, role} = response.data;
                    const isAdmin = (role === "expert" || role === "super_admin" || role === "admin") ? true : false;
                    this.setState(prevState => ({
                        estateInfo: {
                            ...prevState.estateInfo,
                            name,
                            email,
                            phone,
                            telephone,
                        },
                        isAuthenticated: true,
                        isAdmin
                    }));

                });
        }

        const category = this.props.match.params.category;
        // axios.get('/api/cities').then((response) => {
        //     this.setState({
        //         cities: _.mapKeys(response.data, 'id')
        //     })
        // }).catch(error => {
        //     console.log(error)
        // });

        this.setState(prevState => ({
            estateInfo: {
                ...prevState.estateInfo,
                category: category,
            },
            category: category,
        }));

    }

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
    // static emailValidation = (email) => {
    //     let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    //     return re.test(String(email).toLowerCase());
    // };
    //
    // onEmailChange = (e) => {
    //     const email = e.target.value;
    //     let emailValidation = "error";
    //     if(RegistrationComponent.emailValidation(email.trim())){
    //         emailValidation = "success";
    //     }
    //
    //     if(email.length <= 45){
    //         this.setState(() => ({email, emailValidation}));
    //     }
    // };

    handleUserInputChange = (e) => {
        const name = e.target.name;
        const value = e.target.value;
        this.setState(prevState => ({
            estateInfo: {
                ...prevState.estateInfo,
                [name]: value
            }
        }));
    }
    handleChangeFacility = (e) => {
        const item = e.target.name;
        const isChecked = e.target.checked;
        this.setState(prevState => ({checkedFacilities: prevState.checkedFacilities.set(item, isChecked)}));
    };
    handleCheckChieldElement = (event) => {

        let facilities = this.state.facilities;
        _.map(facilities, facility => {

            if (facility.slug === event.target.name)
                facility.isChecked = event.target.checked;

            if (event.target.name === 'cabinet' && event.target.checked === false)
                delete this.state.fields.type_cabinet["value"];

        });
        this.setState({facilities: facilities});
    };
    nextPage = () => {
        this.setState({page: this.state.page + 1})

    };

    previousPage = () => {
        this.setState({errors: {}});
        this.setState({page: this.state.page - 1})
    };

    changeCity = (city) => {
        this.changeAdvertismentInfo('city_id', city.cityId);
        this.props.changeSelectedCity(city.citySlug);

        const url = `/api/getRegions/${city.cityId}`;
        axios.get(url).then((response) => {
            this.setState({
                regions: response.data,
            })
        }).catch(error => {
            console.log(error)
        });
        this.nextPage();
    }

    getFacilities = () => {
        if (this.state.fields.facility) {
            axios.get('/api/getFacilities').then((response) => {
                this.setState({
                    facilities: _.mapKeys(response.data, 'slug'),
                })
            }).catch(error => {
                console.log(error)
            });
        }
    };
    getFields = (params) => {
        axios.get('/api/getFields/', {params}).then((response) => {
            const mapFields = _.mapKeys(response.data, 'name');
            const email = {id: 24, name: 'email', value: this.state.estateInfo.email, validation: 'nullable|email'};
            const fields = {...mapFields, email};
            this.setState({
                fields
            }, () => this.getFacilities());
        }).catch(error => {
            console.log(error)
        });

    };
    changeType = (typeId) => {
        this.changeAdvertismentInfo('type_id', typeId);
        const category = this.state.estateInfo.category;
        const params = {
            type_id: typeId,
            category: category
        };
        this.getFields(params);

        this.nextPage();

    }
    ownerChange = (owner) => {
        this.setState({owner: owner});
        this.nextPage();
    };
    changeFieldsValue = (e) => {
        const target = e.target;
        const name = target.name;
        const value = target.type === 'checkbox' ? target.checked : target.value;
        if (name === 'delivery') {
            if (value === null) {

                this.setState({'category': 'sell'});
            } else {
                this.setState({'category': 'presell'});

            }
        }

        let fields = this.state.fields;
        _.map(fields, field => {

            if (field.name === name) {
                if (name === "loan_amount") {
                    field.value = value.replace(/(,| )/g, '');
                } else {
                    field.value = value;
                }
                if (name === 'exchange' && value === false) {
                    delete fields.exchange_with["value"];
                    delete fields.exchange["value"];
                }
                if (name === 'has_loan' && value === false) {
                    delete fields.loan_amount["value"];
                    delete fields.has_loan["value"];
                }
                if (name === 'presell' && value === false) {
                    delete fields.delivery["value"];
                    delete fields.presell["value"];
                }
            }
        });
        this.setState({fields});
    };
    handleMultiSelectChange = (name, selectedOption) => {
        const selectedValue = selectedOption.map(option => (option.value));
        console.log(name,selectedValue);
        // const selectedName = name + '_selected';
        let fields = this.state.fields;
        _.map(fields, field => {

            if (field.name === name) {
                field.value = selectedValue;
            }
        });
        this.setState({fields});
    };

    uplaodFiles = (files) => {
        this.setState({
            files
        });
    };
    moveFiles = () => {

        const params = {
            estate_id: this.state.estateId,
            imagesId: this.state.files
        };
        axios.get("/api/set-estate-photos", {params: params}).then(response => {
            const done = response.data;
            if (done) {
                // this.removeDroppedFile(image.preview);
                // this.calculateProgress(total_files, ++uploaded);
            }
        });
    };

    handleSubmitForm = (e) => {
        this.setState({isLoading: true});

        e.preventDefault();
        // const selectedRegionId = [].filter.call(this.refs.region.options, o => o.selected).map(o => o.value);
        // const selectedPlanId = [].filter.call(this.refs.plan.options, o => o.selected).map(o => o.value);
        const estate = {
            ...this.state.estateInfo,
            owner: this.state.owner,
            category: this.state.category,
            // exchange: this.refs.exchange.checked,
            facilities: this.state.facilities,
            fields: this.state.fields,
        };

        const access_token = window.localStorage.getItem(ACCESS_TOKEN);
        // if(access_token !== null){
        let headers;
        // if (this.props.authentication.isAuthenticated && access_token !== null) {
        if (access_token !== null) {
            headers = {
                Accept: "application/json",
                Authorization: `Bearer ${access_token}`
            };
        }
        axios.post('/api/estates/store', estate, {headers: {...headers}})
            .then(response => {
                const {code, id} = response.data;
                this.props.changeDisabledCity(false);
                this.setState({errors: {}});
                if (this.state.files.length) {
                    this.setState({
                        estateId: id
                    }, () => {
                        this.moveFiles()
                    });
                }
                this.props.history.push(`/manage/${code}`);
                this.setState({isLoading: false});

            }).catch(error => {
            const {errors} = error.response.data;
            this.setState({
                isLoading: false,
                errors
            }, () => this.handleSubmitFail(errors))
        });

    };
    handleSubmitFail = (errors) => {
        document.getElementsByClassName('has-error')[0].focus();
        console.log(errors);
        // // Scroll to first error
        // let key = Object.keys(errors).reduce((k,l) => {
        //     return (document.getElementsByName(k)[0].offsetTop < document.getElementsByName(l)[0].offsetTop) ? k : l;
        // });
        // window.scrollTo(0, document.getElementsByName(key)[0].offsetTop - 100);
    };

    render() {
        const {estateInfo, owner, types, page, cities, fields, plans, facilities, errors} = this.state;

        return (

            <section id="content">
                {this.state.isLoading && (
                    <LoadingScreen>
                        <span> در حال ثبت ملک ...</span>
                    </LoadingScreen>
                )}

                <div id="msform">
                    <div id="progressbar">
                        <div className="logo-breadcrumb"><img src="/asset/images/registerhome-icon.png" alt=""/>
                        </div>
                        <span
                            className="text-breadcrumb">ثبت برای {this.state.category === 'rent' ? 'رهن و اجاره' : 'فروش'} {estateInfo.type_id ? (types[estateInfo.type_id].name) : 'ملک'} {estateInfo.city_id && (
                            <span> در <i
                                style={{color: 'red'}}> {cities[estateInfo.city_id].name} </i></span>)} </span>
                    </div>
                    {page === 1 && (
                        <fieldset className="register-city">
                            <h2 className="fs-title">یکی از شهرهای زیر را انتخاب کنید: </h2>
                            {_.map(cities, city => (
                                <button
                                    onClick={this.changeCity.bind(this, {cityId: city.id, citySlug: city.slug})}
                                    data-slug={city.slug} key={city.slug} type="button"
                                    className="next action-button">{city.name}</button>
                            ))}
                        </fieldset>
                    )}
                    {page === 2 && (
                        <fieldset className="register-state">
                            <h2 className="fs-title">یکی از گزینه های زیر را انتخاب کنید: </h2>
                            {_.map(types, type => (
                                <button onClick={this.changeType.bind(this, type.id)} type="button"
                                        key={type.id}
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
                        <fieldset>
                            <h2 className="fs-title">یکی از گزینه های زیر را انتخاب کنید: </h2>
                            <button onClick={this.ownerChange.bind(this, 'agent')} type="button"
                                    className="next action-button state-btn"
                            >مشاورین املاک
                            </button>
                            <button onClick={this.ownerChange.bind(this, 'owner')} type="button"
                                    className="next action-button person-btn">شخصی
                            </button>
                            <button onClick={this.previousPage} type="button"
                                    className="previous action-button-previous">بازگشت
                            </button>
                        </fieldset>
                    )}
                    {page === 4 && (
                        <fieldset className="register-form">
                            <form id="register-form" encType="multipart/form-data"
                                  onSubmit={this.handleSubmitForm}
                                  method="POST">
                                <div className="info-person">
                                    <h2 className="ff-title">اطلاعات آگهی دهنده</h2>
                                    <div className="show-person">
                                        <div className="col-sm-3 col-xs-12 mb30">
                                            <div className="field placeholder">
                                                <label htmlFor="LastName"
                                                       className="active"> {owner === 'owner' ? 'نام خانوادگی' : 'نام املاک'}</label>

                                                <input id="LastName" maxLength="50" placeholder="" ref='name'
                                                       name="name" type="text"
                                                       className={errors.name ? "has-error" : ''}
                                                       onChange={this.handleUserInputChange}
                                                       value={estateInfo.name}
                                                    // readOnly={this.state.isAuthenticated && !this.state.isAdmin}
                                                />
                                                {errors.name && (
                                                    <label id="state-name-error" className="error"
                                                           htmlFor="LastName">{errors.name}</label>
                                                )}
                                            </div>
                                        </div>
                                    </div>

                                    <div className="col-sm-3 col-xs-12 mb30">
                                        <div className="field placeholder">
                                            <label htmlFor="phone" className="active">تلفن همراه</label>
                                            <input id="phone" name="phone" min="0" type="number" ref='phone'
                                                   data-category="quantity"
                                                   className={errors.phone ? 'has-error' : ''}
                                                   value={estateInfo.phone}
                                                   onChange={this.handleUserInputChange}
                                                // readOnly={this.state.isAuthenticated && !this.state.isAdmin}
                                            />
                                            {errors.phone && (
                                                <label id="state-name-error" className="error"
                                                       htmlFor="LastName">{errors.phone}</label>
                                            )}

                                        </div>
                                    </div>

                                    {owner === 'agent' && (
                                        <div className="col-sm-3 col-xs-12 mb30">
                                            <div className="field placeholder">
                                                <label htmlFor="tell" className="active"> تلفن
                                                    ثابت(اختیاری)</label>
                                                <input id="tell" name="telephone" ref="telephone" min="0"
                                                       type="number"
                                                       data-category="quantity" placeholder=""
                                                       className={errors.telephone ? 'has-error' : ''}
                                                       value={estateInfo.telephone}
                                                       onChange={this.handleUserInputChange}
                                                       pattern="(0|\+98)?([ ]|-|[()]){0,2}9[1|2|3|4]([ ]|-|[()]){0,2}(?:[0-9]([ ]|-|[()]){0,2}){8}"
                                                       data-msg-pattern="شماره صحیح نیست"/>
                                                {errors.telephone && (
                                                    <label id="state-name-error" className="error"
                                                           htmlFor="LastName">{errors.telephone}</label>
                                                )}

                                            </div>
                                        </div>
                                    )}
                                    <div
                                        className={owner === 'agent' ? "col-sm-3 col-xs-12 mb30" : "col-sm-6 col-xs-12 mb30"}>
                                        <div className="field placeholder">
                                            <label htmlFor="email" className="active">ایمیل</label>
                                            <input id="email" name="email" ref="email" type="email"
                                                   placeholder="لینک مدیریت به ایمیل شما ارسال می گردد."
                                                   value={fields.email && fields.email.value}
                                                   onChange={this.changeFieldsValue}
                                                   className={errors.email ? 'has-error' : ''}
                                                   email="true"/>
                                            {errors.email && (
                                                <label id="state-name-error" className="error"
                                                       htmlFor="LastName">{errors.email}</label>
                                            )}
                                        </div>
                                    </div>
                                </div>

                                <div className="info-state">
                                    <h2 className="ff-title"> مشخصات اصلی ملک</h2>
                                    <div className="col-sm-3 col-xs-6 mb30">
                                        <div className="field placeholder">
                                            <label htmlFor="position" className="active">موقعیت</label>
                                            <select id="position" className="label" ref="plan" name="plan_id"
                                                    value={estateInfo.plan_id}
                                                    onChange={this.handleEstateInputChange}
                                                    data-rule-required="true"
                                                    className={errors.plan_id ? 'has-error' : ''}
                                            >
                                                <option value="" hidden>انتخاب کنید</option>
                                                {plans.map(plan => (
                                                    <option className="light-gray" key={plan.id}
                                                            value={plan.id}>{plan.name}</option>
                                                ))}
                                            </select>
                                            {errors.plan_id && (
                                                <label className="error"> {errors.plan_id} </label>
                                            )}
                                        </div>
                                    </div>

                                    <div className="col-sm-3 col-xs-6 mb30">
                                        <div className="field placeholder">
                                            <label htmlFor="area" className="active">متراژ (متر مربع)</label>
                                            <input id="area" ref="area" name="area" min="0" type="tel"
                                                   className={errors.area ? 'has-error currency' : 'currency'}
                                                   value={estateInfo.area ? estateInfo.area.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : ''}
                                                   onChange={this.handleEstateInputChange}
                                            />
                                            {errors.area && (
                                                <label className="error">{errors.area}</label>
                                            )}
                                        </div>
                                    </div>

                                    <div className="col-sm-3 col-xs-6 mb30">
                                        <div className="field placeholder">
                                            <label htmlFor="price"
                                                   className="active">{this.state.estateInfo.category === 'rent' ? 'مبلغ اجاره(تومان)' : 'قیمت متر مربع(تومان)'}</label>
                                            <input id="price" ref="price" name="price" min="0" type="tel"
                                                   className={errors.price ? 'has-error currency' : 'currency'}
                                                   value={estateInfo.price ? estateInfo.price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : ''}
                                                   onChange={this.handleEstateInputChange}
                                            />
                                            {errors.price && (
                                                <label className="error">{errors.price}</label>
                                            )}
                                        </div>
                                    </div>
                                    <div className="col-sm-3 col-xs-6 mb30">
                                        <div className="field placeholder">
                                            <label htmlFor="totalPrice"
                                                   className="active">{this.state.estateInfo.category === 'rent' ? 'مبلغ رهن(تومان)' : 'قیمت کل (تومان)'}</label>
                                            <input id="totalPrice" ref="totalPrice" min="0" type="tel"
                                                   name="total_price"
                                                   className={errors.total_price ? 'has-error currency' : 'currency'}
                                                   value={estateInfo.total_price ? estateInfo.total_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : ''}
                                                   onChange={this.handleEstateInputChange}
                                            />
                                            {errors.total_price && (
                                                <label className="error">{errors.total_price}</label>
                                            )}
                                        </div>
                                    </div>

                                    {fields.age && (
                                        <div className="col-sm-3 col-xs-6 mb30">
                                            <div className="field placeholder">
                                                <label htmlFor="age" className="active">عمر بنا(سال)</label>
                                                <input id="age" name="age" ref="age" min="0" type="number"
                                                       className={errors['fields.age.value'] ? 'has-error mb0' : 'mb0'}
                                                       value={(fields.delivery && fields.delivery.value) ? 0 : fields['age'].value}
                                                       onChange={this.changeFieldsValue}
                                                       placeholder="نوساز=0 "/>

                                                {errors['fields.age.value'] && (
                                                    <label
                                                        className="error">{errors['fields.age.value']}</label>
                                                )}
                                            </div>
                                        </div>
                                    )}
                                    {fields.room && (
                                        <div className="col-sm-3 col-xs-6 mb30">
                                            <div className="field placeholder">
                                                <label htmlFor="room" className="active">تعداد اتاق</label>
                                                <input id="room" name="room" ref="room" min="0" type="number"
                                                       className={errors['fields.room.value'] ? 'has-error' : ''}
                                                       value={fields['room'].value}
                                                       onChange={this.changeFieldsValue}
                                                />
                                                {errors['fields.room.value'] && (
                                                    <label
                                                        className="error">{errors['fields.room.value']}</label>
                                                )}
                                            </div>
                                        </div>
                                    )}
                                    {fields.floor && (
                                        <div className="col-sm-3 col-xs-6 mb30">
                                            <div className="selectwrap field placeholder">

                                                <label htmlFor="floor" className="active">طبقه</label>
                                                <div className="multiselect-arrow">
                                                    <ReactMultiSelectCheckboxes options={floors}
                                                                                isMulti={true}
                                                                                styles={multiSelectCustomStyle}
                                                                                name="floor"
                                                                                onChange={this.handleMultiSelectChange.bind(this, 'floor')}
                                                                                placeholderButtonLabel={<span
                                                                                    className="light-gray-select"> انتخاب کنید .. </span>}
                                                                                hideSearch={true}
                                                                                isRtl={true}
                                                    />
                                                </div>
                                                {errors['fields.floor.value'] && (
                                                    <label
                                                        className="error">{errors['fields.floor.value']}</label>
                                                )}

                                            </div>
                                        </div>
                                    )}
                                    {fields.unit && (

                                        <div className="col-sm-3 col-xs-6 mb30">
                                            <div className="field placeholder">
                                                <label htmlFor="unit" className="active">تعداد کل واحد</label>
                                                <input id="unit" ref="unit" name="unit" min="0" type="number"
                                                       className={errors['fields.unit.value'] ? 'has-error' : ''}
                                                       value={fields['unit'].value}
                                                       onChange={this.changeFieldsValue}/>
                                                {errors['fields.unit.value'] && (
                                                    <label
                                                        className="error">{errors['fields.unit.value']}</label>
                                                )}
                                            </div>
                                        </div>
                                    )}
                                    {fields.housting && (

                                        <div className="col-sm-3 col-xs-6 mb30">
                                            <div className="field placeholder">
                                                <label htmlFor="housting" className="active">وضعیت سکونت</label>

                                                <select id="housting"
                                                        className={errors.housting ? 'has-error label' : 'label'}
                                                        ref="housting" name="housting"
                                                        value={fields['housting'].value}
                                                        onChange={this.changeFieldsValue}

                                                >
                                                    <option value="" hidden>انتخاب کنید</option>
                                                    <option className="light-gray" value="تخلیه">تخلیه</option>
                                                    <option className="light-gray" value="ملک در اجاره است">ملک
                                                        در اجاره است
                                                    </option>
                                                    <option className="light-gray" value="مالک سکونت دارد">مالک
                                                        سکونت دارد
                                                    </option>
                                                </select>
                                                {errors['fields.housting.value'] && (
                                                    <label className="error">{errors['fields.housting.value']}</label>
                                                )}
                                            </div>
                                        </div>
                                    )}
                                    {fields.doc && (
                                        <div className="col-sm-3 col-xs-6 mb30">
                                            <div className="field placeholder">
                                                <label htmlFor="type-doc" className="active">نوع سند</label>

                                                <select id="type-doc" name="doc"
                                                        className={errors['fields.doc.value'] ? 'has-error label' : 'label'}
                                                        value={fields['doc'].value}
                                                        onChange={this.changeFieldsValue}
                                                >
                                                    <option className="light-gray" value="" hidden>انتخاب کنید
                                                    </option>
                                                    <option className="light-gray" value="سند ملکی">سند ملکی
                                                    </option>
                                                    <option className="light-gray" value="اوقافی">اوقافی
                                                    </option>
                                                    <option className="light-gray" value="قلنامه ای">قلنامه ای
                                                    </option>
                                                    <option className="light-gray" value="مشاع">مشاع</option>
                                                    <option className="light-gray" value="سرقفلی">سرقفلی
                                                    </option>
                                                </select>
                                                {errors['fields.doc.value'] && (
                                                    <label
                                                        className="error">{errors['fields.doc.value']}</label>
                                                )}
                                            </div>
                                        </div>
                                    )}
                                    {fields.has_loan && (

                                        <div className="col-sm-3 col-xs-6 mb30">
                                            <div className="col-sm-1 col-xs-2 pr0 nowrap t10 check-text">
                                                <div className="checkbox"
                                                     style={{textAlign: 'right', right: '-11px'}}>

                                                    <label htmlFor='has_loan' style={{paddingRight: 0}}>
                                                        <input onChange={this.changeFieldsValue} type="checkbox"
                                                               id="has_loan"
                                                               name="has_loan"
                                                               style={{width: 35, marginRight: 0}}/>
                                                        {!fields['has_loan'].value && (
                                                            'وام دارد'
                                                        )}
                                                    </label>
                                                </div>
                                            </div>

                                            {fields['has_loan'].value && (
                                                <div className="col-md-11 col-xs-10 field placeholder mp15 pl0 pr0">
                                                    <label htmlFor="loan_amount" className="active">مبلغ وام
                                                        (تومان)</label>

                                                    <input id="loan_amount" name="loan_amount" min="0"
                                                           type="tel"
                                                           className={errors['fields.loan_amount.value'] ? 'has-error currency' : 'currency'}
                                                           value={fields['loan_amount'].value ? fields['loan_amount'].value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : ''}
                                                           onChange={this.changeFieldsValue}
                                                    />
                                                    {errors['fields.loan_amount.value'] && (
                                                        <label
                                                            className="error">{errors['fields.loan_amount.value']}</label>
                                                    )}

                                                </div>
                                            )}
                                        </div>
                                    )}
                                    {fields.presell && (
                                        <div className="col-sm-3 col-xs-6 mb30">
                                            <div className="col-md-1 col-xs-2 pr0 nowrap t10 check-text">
                                                <div className="checkbox"
                                                     style={{textAlign: 'right', right: '-11px'}}>
                                                    <label htmlFor='presell' style={{paddingRight: 0}}>
                                                        <input onChange={this.changeFieldsValue} type="checkbox"
                                                               id="presell" name='presell'
                                                               style={{width: 35, marginRight: 0}}/>
                                                        {!fields['presell'].value && (
                                                            ' پیش فروش'
                                                        )}
                                                    </label>
                                                </div>
                                            </div>
                                            {fields['presell'].value && (
                                                <div
                                                    className="col-md-11 col-xs-10 field placeholder mp15 pl0 pr0">
                                                    <label htmlFor="delivery" className="active">زمان
                                                        تحویل</label>
                                                    <select id="delivery"
                                                            className={errors['fields.delivery.value'] ? 'has-error label' : 'label'}
                                                            name="delivery"
                                                            value={fields['delivery'].value}
                                                            onChange={this.changeFieldsValue}>
                                                        <option value="" hidden>انتخاب کنید</option>
                                                        <option className="light-gray" value="1">1 ماه آینده
                                                        </option>
                                                        <option className="light-gray" value="2">2 ماه آینده
                                                        </option>
                                                        <option className="light-gray" value="3">3 ماه آینده
                                                        </option>
                                                        <option className="light-gray" value="4">4 ماه آینده
                                                        </option>
                                                        <option className="light-gray" value="5">5 ماه آینده
                                                        </option>
                                                        <option className="light-gray" value="6">6 ماه آینده
                                                        </option>
                                                        <option className="light-gray" value="7">7 ماه آینده
                                                        </option>
                                                        <option className="light-gray" value="8">8 ماه آینده
                                                        </option>
                                                        <option className="light-gray" value="9">9 ماه آینده
                                                        </option>
                                                        <option className="light-gray" value="10">10 ماه آینده
                                                        </option>
                                                        <option className="light-gray" value="11">11 ماه آینده
                                                        </option>
                                                        <option className="light-gray" value="12">12 ماه آینده
                                                        </option>
                                                        <option className="light-gray" value="13">13 ماه آینده
                                                        </option>
                                                        <option className="light-gray" value="14">14 ماه آینده
                                                        </option>
                                                        <option className="light-gray" value="15">15 ماه آینده و
                                                            بیشتر
                                                        </option>
                                                    </select>
                                                    {errors['fields.delivery.value'] && (
                                                        <label
                                                            className="error">{errors['fields.delivery.value']}</label>
                                                    )}
                                                </div>
                                            )}
                                        </div>
                                    )}
                                    {fields.bahr && (
                                        <div className="col-sm-3 col-xs-6 mb30">
                                            <div className="field placeholder">
                                                <label htmlFor="lenght" className="active">عرض زمین(متر)</label>
                                                <input id="lenght" name="bahr" min="0" type="number" step={"any"}
                                                       className={errors['fields.bahr.value'] ? 'has-error currency' : 'currency'}
                                                       value={fields['bahr'].value}
                                                       onChange={this.changeFieldsValue}
                                                       data-category="quantity" placeholder=""
                                                       data-rule-required="true"
                                                       data-msg-required="این فیلد الزامی است"/>
                                                {errors['fields.bahr.value'] && (
                                                    <label
                                                        className="error">{errors['fields.bahr.value']}</label>
                                                )}
                                            </div>
                                        </div>
                                    )}

                                    {fields.tarakom && (
                                        <div className="col-sm-3 col-xs-6 mb30">
                                            <div className="field placeholder">
                                                <label htmlFor="tarakom" className="active">تراکم</label>
                                                <select id="tarakom"
                                                        className={errors['fields.tarakom.value'] ? 'has-error label' : 'label'}
                                                        name="tarakom"
                                                        value={fields['tarakom'].value}
                                                        onChange={this.changeFieldsValue}
                                                        data-rule-required="true"
                                                        data-msg-required="این فیلد الزامی است">
                                                    <option className="light-gray" value="" hidden>انتخاب کنید
                                                    </option>
                                                    <option className="light-gray" value="1">1 طبقه بالای
                                                        پیلوت
                                                    </option>
                                                    <option className="light-gray" value="2">2 طبقه بالای
                                                        پیلوت
                                                    </option>
                                                    <option className="light-gray" value="3">3 طبقه بالای
                                                        پیلوت
                                                    </option>
                                                    <option className="light-gray" value="4">4 طبقه بالای
                                                        پیلوت
                                                    </option>
                                                    <option className="light-gray" value="5">5 طبقه بالای
                                                        پیلوت
                                                    </option>
                                                    <option className="light-gray" value="6">6 طبقه بالای
                                                        پیلوت
                                                    </option>
                                                    <option className="light-gray" value="7">7 طبقه بالای
                                                        پیلوت
                                                    </option>
                                                    <option className="light-gray" value="8">8 طبقه بالای
                                                        پیلوت
                                                    </option>
                                                    <option className="light-gray" value="9">9 طبقه بالای
                                                        پیلوت
                                                    </option>
                                                    <option className="light-gray" value="10">10 طبقه بالای
                                                        پیلوت و
                                                        بیشتر
                                                    </option>
                                                </select>
                                                {errors['fields.tarakom.value'] && (
                                                    <label
                                                        className="error">{errors['fields.tarakom.value']}</label>
                                                )}
                                            </div>
                                        </div>
                                    )}
                                    {fields.exchange && (
                                        <div className="col-sm-3 col-xs-6 mb30">

                                            <div className="col-sm-1 col-xs-2 pr0 nowrap t10 check-text">
                                                <div className="checkbox"
                                                     style={{textAlign: 'right', right: '-11px'}}>
                                                    <label htmlFor='has_exchange' style={{paddingRight: 0}}>
                                                        <input ref="exchange" onChange={this.changeFieldsValue}
                                                               type="checkbox" id="has_exchange" name="exchange"
                                                               style={{width: 35, marginRight: 0}}/>
                                                        {!fields['exchange'].value && (
                                                            'معاوضه'
                                                        )}
                                                    </label>
                                                </div>
                                            </div>

                                            {fields['exchange'].value && (
                                                <div
                                                    className="col-md-11 col-xs-10 field placeholder mp15 pl0 pr0">
                                                    <label htmlFor="exchange_with" className="active">معاوضه
                                                        با</label>

                                                    <input id="exchange_with" name="exchange_with" type="text"
                                                           value={fields['exchange_with'].value}
                                                           onChange={this.changeFieldsValue}
                                                           className={errors['fields.exchange_with.value'] ? 'has-error' : ''}
                                                           maxLength="25"
                                                    />
                                                    {errors['fields.exchange_with.value'] && (
                                                        <label
                                                            className="error">{errors['fields.exchange_with.value']}</label>
                                                    )}
                                                </div>
                                            )}

                                        </div>
                                    )}

                                    <div className="col-sm-3 col-xs-12 mb30">
                                        <div className="field placeholder">
                                            <label htmlFor="location" className="active">منطقه</label>

                                            <select ref="region" id="location"
                                                    className={errors.region_id ? 'has-error label' : 'label'}
                                                    name="region_id"
                                                    value={estateInfo.region_id}
                                                    onChange={this.handleEstateInputChange}
                                                    data-rule-required="true"
                                                    data-msg-required="این فیلد الزامی است">
                                                <option value="" hidden>انتخاب کنید</option>
                                                {this.state.regions.map(region => (
                                                        <option value={region.id}
                                                                key={region.id}>{region.name}</option>

                                                    )
                                                )}
                                            </select>
                                            {errors.region_id && (
                                                <label className="error">{errors.region_id}</label>
                                            )}
                                        </div>
                                    </div>
                                    {fields.address && (
                                        <div className="col-sm-6 col-xs-12 mb30">
                                            <div className="field placeholder">
                                                <label htmlFor="address" className="active">آدرس</label>
                                                <textarea ref="address" name="address"
                                                          id="address" cols="30"
                                                          rows="1"
                                                          className={errors['fields.address.value'] ? 'has-error' : ''}
                                                          onChange={this.changeFieldsValue}>{fields['address'].value}</textarea>
                                                {errors['fields.address.value'] && (
                                                    <label
                                                        className="error">{errors['fields.address.value']}</label>
                                                )}
                                            </div>
                                        </div>
                                    )}
                                </div>

                                {fields.facility && (
                                    <div className="facilities mb20">

                                        <h2 className="ff-title"> امکانات</h2>

                                        {_.map(facilities, facility => (

                                            <div className="col-md-2 col-xs-6 mb20" key={facility.slug}>
                                                <input onChange={this.handleCheckChieldElement}
                                                       id={facility.slug}
                                                       ref={facility.slug} name={facility.slug} type="checkbox"
                                                       checked={facility.isChecked}/>
                                                <label htmlFor={facility.slug} className="hasLoan">
                                                    {facility.name}
                                                </label>
                                            </div>
                                        ))}

                                        {facilities['cabinet'] && facilities['cabinet'].isChecked && (
                                            <div className=" col-xs-6 col-md-2 field placeholder"
                                                 style={{right: '-16px'}}>
                                                <select id="type_cabinet"
                                                        value={fields['type_cabinet'].value}
                                                        onChange={this.changeFieldsValue}
                                                        className={errors['fields.type_cabinet.value'] ? 'has-error label' : 'label'}
                                                        name="type_cabinet">
                                                    <option className="light-gray" value="" hidden>انتخاب
                                                        کنید
                                                    </option>
                                                    <option className="light-gray" value="فلزی">فلزی
                                                    </option>
                                                    <option className="light-gray" value="mdf">mdf</option>
                                                    <option className="light-gray" value="چوب">چوب</option>
                                                    <option className="light-gray" value="طرح mdf">طرح mdf
                                                    </option>
                                                    <option className="light-gray" value="های گلاس">های
                                                        گلاس
                                                    </option>
                                                    <option className="light-gray" value="ممبران">ممبران
                                                    </option>
                                                    <option className="light-gray" value="سایر">سایر
                                                    </option>
                                                </select>
                                                <label htmlFor="type_cabinet" className="active"> نوع
                                                    کابینت </label>
                                                {errors['fields.type_cabinet.value'] && (
                                                    <label
                                                        className="error">{errors['fields.type_cabinet.value']}</label>
                                                )}
                                            </div>
                                        )}
                                    </div>
                                )}
                                <div className="col-md-12 col-xs-12 explain">
                                    <h6 className="ff-title"> افزودن تصاویر(ثبت با تصویر امکان دیده شدن ملک را
                                        تا 5
                                        برابر بیشتر می کند)</h6>

                                    <div className="field placeholder">
                                        <ImageDropzone images={[]} uplaodFiles={this.uplaodFiles}/>
                                    </div>
                                </div>
                                {fields.description && (

                                    <div className="col-md-12 col-xs-12 mt30">
                                        <div className="field placeholder">
                                            <pre>
                                                <textarea name="description" id="description" cols="30" rows="6"
                                                          onChange={this.changeFieldsValue}>{fields['description'].value}</textarea>
                                                </pre>
                                            <label htmlFor="description" className="active">توضیحات </label>
                                        </div>
                                    </div>
                                )}
                                <input type="hidden" className="has-error"/>

                                <div className="col-md-12 col-xs-12 ">
                                    <button onClick={this.previousPage} type="button"
                                            className="previous action-button-previous"
                                    >بازگشت
                                    </button>
                                    <button type="submit" id="submit-form" name="submit"
                                            className={`action-button ${this.state.isLoading ? 'active' : ''}`}
                                            value="Submit">ارسال رایگان آگهی
                                    </button>
                                </div>

                            </form>
                        </fieldset>
                    )}
                </div>
            </section>
        )

    }
}

export default connect(null, {changeSelectedCity, changeDisabledCity})(WizardForm)

