import React, {Component} from 'react';
import axios from 'axios';
import {Link} from 'react-router-dom';
import LoadingScreen from "./LoadingScreen";
import Footer from '../components/Footer';
import {ACCESS_TOKEN} from "../api/strings";
import GridEstate from "./GridEstate";
import '../../sass/singleshow.css';
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";
import {withRouter} from "react-router-dom";
import InformationPanel from "./InformationPanel";
import Gallery from "./Partials/Gallery";
import ReportBug from "./ReportBug";

const GridRouterEstate = withRouter(GridEstate);

const Modal = ({handleClose, show, children}) => {
    const showHideClassName = show ? 'modal display-block' : 'modal display-none';

    return (
        <div className={showHideClassName}>
            <section className='modal-main col-xs-10 col-sm-6'>
                {children}

            </section>
        </div>
    );
};

class Estate extends Component {
    constructor(props) {
        super(props);
        this.state = {
            isLoading: true,
            productNotFound: false,
            estate: {},
            isType: false,
            isRegion: false,
            isEstate: false,
            similarEstates: [],
            contact: {
                showContact: false,
                disabled: false,
            },
            showModal: false,
            showMore: false,
            marked: false,
        };
    }

    showModalHandle = (e) => {
        e.preventDefault();
        this.setState({
            showModal: true
        })
    };
    hideModalHandle = () => {
        this.setState({
            showModal: false,
        })
    };
    showContactChange = () => {
        const estateId = this.state.estate.id;
        axios.get(`/api/getUserContact/${estateId}`)
            .then(response => {
                const phones = response.data.phones;
                const title = response.data.title;
                this.setState({
                    contact: {
                        disabled: true,
                        showContact: true,
                        phones,
                        title
                    }
                })
            });
    };
    loadProductDetails = (id) => {
        // this.setState({ isLoading:true});
        const access_token = window.localStorage.getItem(ACCESS_TOKEN);
        let headers;

        if (access_token !== null) {
            headers = {Accept: "application/json", Authorization: `Bearer ${access_token}`};
        }
        axios.get(`/api/estate/${id}`, {headers: {...headers}})
            .then((response) => (this.setState({
                auth: response.data.auth,
                estate: response.data.estate,
                facilities: response.data.facilities,
                similarEstates: response.data.similarEstates,
                isLoading: false,
                productNotFound: false,
            }, () => this.handleBookmarked))).catch((error) => (this.setState({

                productNotFound: true,
                isLoading: false,
            }))
        );
    };

    componentDidMount() {
        const {id} = this.props.match.params;
        if (window.localStorage.getItem('myBookmarkedEstate') !== null) {
            let markedIds = JSON.parse(window.localStorage.getItem('myBookmarkedEstate'));
            if (markedIds.indexOf(parseInt(id)) > -1) {
                console.log('markedIds.indexOf(parseInt(id) > -1)');
                this.setState({marked: true});
            }
            // markedIds = JSON.stringify(markedIds);
            console.log(typeof markedIds, markedIds, markedIds.indexOf(parseInt(id)), parseInt(id));

        }
        this.loadProductDetails(id);
        window.scrollTo(0, 0);
    }

    componentWillReceiveProps(nextProps) {
        if (nextProps.location.state === 'desiredState') {
            console.log(nextProps.location.pathname);

            // this.props.history.push(`/manage/${code}`);
        }
    }

    changeDarkhast = (url) => {
        this.setState({
            showModal: false
        })
        this.props.history.push(url);
    }

    renderOfferPhotoSwitch(param) {
        console.log(param);
        switch (param) {
            case 'lux':
                return (<div className="ribbon-box red">
                    <span className="ribbon">لوکس</span>
                </div>);
            case 'underprice':
                return (<div className="ribbon-box blue">
                    <span className="ribbon">زیرقیمت</span>
                </div>);
            case 'special':
                return (<div className="ribbon-box green">
                    <span className="ribbon">ویژه</span>
                </div>);
            default:
                return;
        }
    }

    addBookmarkedEstate = () => {
        const estateId = this.state.estate.id;
        let bookmarkedEstate = window.localStorage.getItem('myBookmarkedEstate');

        bookmarkedEstate = (bookmarkedEstate) ? JSON.parse(bookmarkedEstate) : [];

        if (this.state.marked) {
            //remove from localStorage
            bookmarkedEstate.splice(bookmarkedEstate.indexOf(parseInt(estateId)), 1);
            this.setState({marked: false})
        } else {
            // add to localStorage
            this.setState({marked: true});
            if (bookmarkedEstate.indexOf(parseInt(estateId)) === -1) {
                bookmarkedEstate.push(estateId);
            }
        }
        localStorage.setItem("myBookmarkedEstate", JSON.stringify(bookmarkedEstate));
    };

    render() {
        const {similarEstates, estate, contact, auth, facilities, showMore} = this.state;
        const {fields, experts} = this.state.estate;

        if (this.state.isLoading) {
            return (
                <LoadingScreen>
                    <span> در حال بارگذاری ملک ...</span>
                </LoadingScreen>
            );
        }
        else if (this.state.productNotFound) {
            return (
                <div class="content-boxes">
                    <InformationPanel
                        panelTitle={"ملک مورد نظر یافت نشد"}
                        informationHeading={"ملکی با این کد وجود ندارد!"}
                        message={"ملک مورد نظر یافت نشد."}
                    />
                </div>
            );
        }
        return (

            <section id="content">
                <Modal show={this.state.showModal} handleClose={this.hideModalHandle}>
                    <div className="modal-dialog modal-md" style={{width: 'auto'}}>
                        <div className="modal-content">
                            <div className="modal-header">
                                <button type="button" onClick={this.hideModalHandle} className="close"
                                        data-dismiss="modal">×
                                </button>
                                <h4 className="modal-title">نوع درخواست </h4>
                            </div>
                            <div className="modal-body">
                                <div className={"col-md-6 text-center"}>
                                    <button onClick={this.changeDarkhast.bind(this, '/darkhast/sell')}
                                            className="btn btn-success " role="button"> درخواست خرید
                                        ملک
                                    </button>
                                </div>
                                <div className={"col-md-6 text-center"}>
                                    <button onClick={this.changeDarkhast.bind(this, '/darkhast/rent')}
                                            className="btn btn-success" role="button"> درخواست اجاره
                                        ملک
                                    </button>
                                </div>
                            </div>
                            <div className="modal-footer">
                                <button type="button" onClick={this.hideModalHandle} className="btn btn-danger"
                                        data-dismiss="modal">بستن
                                </button>
                            </div>
                        </div>
                    </div>
                </Modal>

                <div className="app-wrapper" data-reactroot="">
                    <div className="col-md-10 col-md-offset-1 col-xs-12 single-show">
                        <div className="post-page">
                            <div className="ui fluid card breadcrumb-container">
                                <div className="content">
                                    <button className="ui button back-button" role="button"
                                            onClick={() => this.props.history.go(-1)}>بازگشت
                                    </button>
                                    <div className="ui breadcrumb">
                                        <span>
                                            <div className="section">
                                                <Link to='/' title="همه آگهی ها">همه آگهی ها</Link>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div className="row">
                                <div className="col-sm-5 col-xs-no-padding col-xs-12">
                                    <div className="ui fluid card post-info business-card">
                                        <div className="content">
                                            <h1>{estate.title}</h1>
                                            <span className="publishTime">{estate.date}</span>
                                        </div>
                                    </div>
                                    <div className="ui fluid card post-actions">
                                        <div className="content">
                                            <div className="post-contact-methods">
                                                <button onClick={this.showContactChange}
                                                        className={`ui button get-contact ${contact.disabled === true ? 'disabled' : ''}`}
                                                        role="button">دریافت
                                                    اطلاعات
                                                    تماس
                                                </button>
                                            </div>
                                            <button onClick={this.addBookmarkedEstate}
                                                    className={this.state.marked ? "ui button bookmark marked" : "ui button bookmark"}
                                                    role="button"> {this.state.marked ? 'نشان شده' : 'نشان کردن'}
                                            </button>
                                        </div>
                                    </div>
                                    <div className="ui fluid card post-fields">
                                        <div className="content">
                                            <div className="info-home">
                                                {contact.showContact && (
                                                    <div>
                                                        {contact.phones && (
                                                            <div className="item">
                                                                <label>{contact.title}</label>
                                                                {contact.phones.map((phone, index) => (

                                                                    <a key={index} href={`tel:${phone}`}
                                                                       className=" value concat"> {phone} <i
                                                                        className="fa fa-phone"></i></a>
                                                                ))}
                                                            </div>
                                                        )}
                                                    </div>
                                                )}

                                                <div className="item">
                                                    <label>متراژ</label>
                                                    <div
                                                        className="value">{estate.area.toLocaleString("en", {minimumFractionDigits: 0})} متر
                                                    </div>
                                                </div>
                                                <div className="item">
                                                    <label>{estate.category === 'rent' ? 'رهن' : 'قیمت کل'}</label>
                                                    <div
                                                        className="value"> {estate.total_price.toLocaleString("en", {minimumFractionDigits: 0})} تومان
                                                    </div>
                                                </div>
                                                <div className="item">
                                                    <label>{estate.category === 'rent' ? 'اجاره' : 'قیمت متر مربع'}</label>
                                                    <div
                                                        className="value">{estate.price.toLocaleString("en", {minimumFractionDigits: 0})} تومان
                                                    </div>
                                                </div>
                                                <div className="item">
                                                    <label>سریال ملک</label>
                                                    <div className="value">{estate.id}</div>
                                                </div>
                                                {'zone' in fields && (
                                                    <div className="item">
                                                        <label> مکان</label>
                                                        <div className="value">{fields['zone']}</div>
                                                    </div>
                                                )}
                                                {'unit' in fields && (
                                                    <div className="item">
                                                        <label>تعداد واحد</label>
                                                        <div className="value">{fields.unit}</div>
                                                    </div>
                                                )}
                                                {'room' in fields && (
                                                    <div className="item">
                                                        <label>تعداد اتاق</label>
                                                        <div className="value"> {fields.room}</div>
                                                    </div>
                                                )}
                                                {fields['floor'] && fields['floor'].length > 0 && (
                                                    <div className="item">
                                                        <label>طبقه</label>
                                                        <div className="value">
                                                            <ul className={"comma-list"}>{fields['floor'].map((floor, index) => (
                                                                <li key={index}>{floor}</li>))}</ul>
                                                        </div>
                                                    </div>
                                                )}
                                                {('age' in fields) && (
                                                    fields['presell']
                                                        ?
                                                        ''
                                                        :
                                                        <div className="item">
                                                            <label>عمر بنا</label>
                                                            <div
                                                                className="value">{(fields.age === 0 || fields.age === '0') ? 'نوساز' : fields.age + ' سال'} </div>
                                                        </div>
                                                )}
                                                {'plan_id' in estate && (

                                                    <div className="item">
                                                        <label>موقعیت </label>
                                                        <div className="value">{estate.plan.name} </div>
                                                    </div>
                                                )}
                                                {'housting' in fields && (

                                                    <div className="item">
                                                        <label> وضعیت سکونت</label>
                                                        <div className="value">{fields['housting']}</div>
                                                    </div>
                                                )}
                                                {'doc' in fields && (

                                                    <div className="item">
                                                        <label> نوع سند </label>
                                                        <div className="value">{fields['doc']}</div>
                                                    </div>
                                                )}
                                                {'tarakom' in fields && (

                                                    <div className="item">
                                                        <label> تراکم </label>
                                                        <div className="value">{fields['tarakom']}</div>
                                                    </div>
                                                )}{'bahr' in fields && (

                                                <div className="item">
                                                    <label> عرض زمین </label>
                                                    <div className="value">{fields['bahr']}</div>
                                                </div>
                                            )}
                                                {'has_loan' in fields && (

                                                    <div className="item">
                                                        <label> مبلغ وام </label>
                                                        <div
                                                            className="value">{fields['loan_amount'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</div>
                                                    </div>
                                                )}
                                                {'exchange' in fields && (

                                                    <div className="item">
                                                        <label> قابل معاوضه با </label>
                                                        <div
                                                            className="value">{fields['exchange_with']}</div>
                                                    </div>
                                                )}
                                                {'presell' in fields && (

                                                    <div className="item">
                                                        <label> پیش فروش </label>
                                                        <div className="value">دارد</div>
                                                    </div>
                                                )}
                                                {'presell' in fields && (

                                                    <div className="item">
                                                        <label> زمان تحویل </label>
                                                        <div className="value">{estate.delivery}
                                                        </div>
                                                    </div>
                                                )}
                                                {'description' in fields && (

                                                    <div className="item">
                                                        <label className="label-bold"> توضیحات </label>
                                                        <div className="value"
                                                             style={{float: 'none', display: 'inline'}}>
                                                            <p className={'pre'}>{fields['description']} </p>
                                                        </div>
                                                    </div>
                                                )}

                                                {(auth && (auth.role === "admin" || auth.role === "super_admin")) && (
                                                    <div>
                                                        <label style={{color: 'red'}} onClick={() => {
                                                            this.setState({showMore: !showMore})
                                                        }}><i
                                                            className={`text-danger ${showMore ? 'fa fa-sort-down' : 'fa fa-sort-up'}`}> </i> اطلاعات
                                                            بیشتر </label>

                                                        {showMore && (

                                                            <div style={{backgroundColor: '#fcbc2826'}}>
                                                                <div className="item">
                                                                    <label> آدرس </label>
                                                                    <div className="value">{fields['address']}</div>
                                                                </div>
                                                                <div className="item">
                                                                    <label> شماره تماس </label>
                                                                    <div
                                                                        className="value">{estate.owner.phone}</div>
                                                                </div>
                                                                <div className="item">
                                                                    <label> نام مالک </label>
                                                                    <div className="value">{estate.owner.name}</div>
                                                                </div>
                                                                {'more' in fields && (
                                                                    <div className="item">
                                                                        <label> توضیحات بیشتر </label>
                                                                        <div
                                                                            className="value">{fields['more']}</div>
                                                                    </div>
                                                                )}
                                                            </div>

                                                        )}
                                                    </div>
                                                )}
                                                {experts && auth && experts.find(expert => expert.id === auth.id) && (
                                                    <div>
                                                        <label onClick={() => {
                                                            this.setState({showMore: !showMore})
                                                        }}><i
                                                            className={`${showMore ? 'fa fa-sort-down' : 'fa fa-sort-up'}`}> </i> اطلاعات
                                                            بیشتر </label>

                                                        {showMore && (

                                                            <div>
                                                                <div className="item">
                                                                    <label> آدرس </label>
                                                                    <div className="value">{fields['address']}</div>
                                                                </div>
                                                                <div className="item">
                                                                    <label> شماره تماس </label>
                                                                    <div
                                                                        className="value">{estate.owner.phone}</div>
                                                                </div>
                                                                <div className="item">
                                                                    <label> نام مالک </label>
                                                                    <div className="value">{estate.owner.name}</div>
                                                                </div>
                                                                {fields['more'] && (
                                                                    <div className="item">
                                                                        <label> توضیحات بیشتر </label>
                                                                        <div
                                                                            className="value">{fields['more']}</div>
                                                                    </div>
                                                                )}
                                                            </div>
                                                        )}
                                                    </div>
                                                )}
                                            </div>

                                            {(fields.facilities && fields.facilities.length > 0) && (
                                                <div className="show-fields.facilities">
                                                    <div className="item">
                                                        <label className="label-bold">امکانات</label>
                                                    </div>
                                                    {fields.facilities.map((facility, i) => (
                                                        <div key={i} className="item col-md-6 col-xs-6">
                                                            <label>
                                                                {facilities[facility]}&nbsp;
                                                                {facility === 'cabinet' && (
                                                                    <span>{fields['type_cabinet'] === 'سایر' ? '' : fields['type_cabinet']}</span>
                                                                )}
                                                            </label>
                                                        </div>
                                                    ))}
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                </div>
                                <div className="col-sm-7  col-xs-12 col-xs-nopadding ">
                                    {!estate.images.length ?
                                        <img src="/asset/images/no-image.png" style={{width: '100%'}}
                                             className="img-responsive col-xs-hidden"/>
                                        :
                                        <Gallery
                                            images={(auth && (auth.role === "admin" || auth.role === "super_admin" || (experts && experts.find(expert => expert.id === auth.id)))) ? estate.images : estate.images.filter(image => (image.status === 1))}/>
                                    }

                                    <div className="post-guidelines-container">
                                        <div className="post-guidelines">
                                            <p>
                                                هنوز ملک خود را پیدا نکرده اید؟! </p>
                                            <a href="#" onClick={this.showModalHandle.bind(this)} data-toggle="modal"
                                               data-target="#myModal"> اینجا کلیک
                                                کنید </a>
                                            تا ملک های جدید برای شما ارسال شود.
                                            <div className="ui divider"></div>
                                            {/*<div className="virtual-tour">*/}
                                            {/*<button className="ui button" role="button"><i*/}
                                            {/*className="fa fa-video-camera"></i> نمایش فیلم*/}
                                            {/*</button>*/}
                                            {/*</div>*/}

                                            {/*<div className="home-video">*/}
                                            {/*<button className="ui button" role="button"><i*/}
                                            {/*className="fa fa-film"></i> نمایش تور مجازی*/}
                                            {/*</button>*/}
                                            {/*</div>*/}
                                            <div className="virtual-tour pull-right">
                                                <ReportBug estateId={estate.id}></ReportBug>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                {similarEstates.length > 0 && (
                    <div className="row">
                        <div className="col-md-10 same-adver  col-md-offset-1">
                            <div className="text-slider-show col-xs-no-padding">ملک های مشابه
                            </div>
                            <div className=" multi-slider">
                                <div className="col-md-12 col-lg-12 no-padding">
                                    {similarEstates.map(estate => (
                                        <div key={estate.id} className="col-sm-6 col-md-4 col-xs-12 col-xs-no-padding">
                                            <GridRouterEstate estate={estate}/>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>
                    </div>
                )}

                <div className="clearfix"></div>
                <div id="show-footer">
                    <Footer/>
                </div>
            </section>
        );
    }
}

export default withRouter(Estate);