import React from 'react';
import InfiniteScroll from 'react-infinite-scroll-component';
import {Grid, Row, Col, Panel} from 'react-bootstrap';
import LoadingScreen from "../components/LoadingScreen";
import {withRouter} from "react-router-dom";
import {
    ANY, MORE_THAN_FOUR, MORE_THAN_THREE, NEW, NO, ONE_TO_THREE, PRICE_HIGH_TO_LOW, PRICE_LOW_TO_HIGH, RATINGS,
    YES
} from "../api/strings";
import {connect} from "react-redux";
import {filterEstates} from '../actions/estates';
import GridEstate from "./GridEstate";
import {changeDisabledCity} from "../actions/city";


class SearchResultsComponent extends React.Component {

    state = {
        sortBySelected: NEW,
        sortByOptions: [PRICE_LOW_TO_HIGH, PRICE_HIGH_TO_LOW, RATINGS],
        activePage: 1,
        totalItemsCount: 55,
        advancedFilterModalShow: false,
        estates: [],
        hasMorEstate: true,
        regions: {
            1: {id: 1, city_id: 1, name: "دانشگاه"},
            2: {id: 2, city_id: 1, name: "سرتک"},
            3: {id: 3, city_id: 1, name: "ولایت"},
            4: {id: 4, city_id: 1, name: "ملاصدرا"},
            5: {id: 5, city_id: 1, name: "جانبازان"},
            6: {id: 6, city_id: 1, name: "پونک"},
            7: {id: 7, city_id: 1, name: "آبگیلک"},
            8: {id: 8, city_id: 1, name: "نوروزیان"},
            9: {id: 9, city_id: 1, name: "کوی قضات"},
            10: {id: 10, city_id: 1, name: "غیاث آباد"},
            11: {id: 11, city_id: 1, name: "شهرک شهید رجایی"},
            12: {id: 12, city_id: 1, name: "مینودر"},
            13: {id: 13, city_id: 1, name: "کوثر"},
            14: {id: 14, city_id: 1, name: "بلوار مدرس"},
            15: {id: 15, city_id: 1, name: "پادگان"},
            16: {id: 16, city_id: 1, name: "عارف"},
            17: {id: 17, city_id: 1, name: "فردوسی"},
            18: {id: 18, city_id: 1, name: "فلسطین"},
            19: {id: 19, city_id: 1, name: "شهید بهشتی"},
            20: {id: 20, city_id: 1, name: "نادری"},
            21: {id: 21, city_id: 1, name: "خیام"},
            22: {id: 22, city_id: 1, name: "توحید"},
            23: {id: 23, city_id: 1, name: "ولیعصر"},
            24: {id: 24, city_id: 1, name: "حکم آباد"},
            25: {id: 25, city_id: 1, name: "بوعلی"},
            26: {id: 26, city_id: 1, name: "شهرداری"},
            27: {id: 27, city_id: 1, name: "طالقانی"},
            28: {id: 28, city_id: 1, name: "ملک آباد"},
            29: {id: 29, city_id: 1, name: "هلال احمر"},
            30: {id: 30, city_id: 1, name: "باغ دبیر"},
            31: {id: 31, city_id: 1, name: "بلاغی"},
            32: {id: 32, city_id: 1, name: "هفت سنگان"},
            33: {id: 33, city_id: 1, name: "مولوی"},
            34: {id: 34, city_id: 1, name: "تهران قدیم"},
            35: {id: 35, city_id: 1, name: "منتظری"},
            36: {id: 36, city_id: 1, name: "شهید انصاری"},
            37: {id: 37, city_id: 1, name: "بلوار اسد آبادی"},
            38: {id: 38, city_id: 1, name: "سپه"},
            39: {id: 39, city_id: 1, name: "پیغمبریه"},
            40: {id: 40, city_id: 1, name: "سعدی"},
            41: {id: 41, city_id: 1, name: "نظام وفا"},
            42: {id: 42, city_id: 1, name: "نواب"},
            43: {id: 43, city_id: 1, name: "خیابان امام"},
            44: {id: 44, city_id: 1, name: "راه آهن"},
            45: {id: 45, city_id: 2, name: "آبیک"},
            46: {id: 46, city_id: 3, name: "تاکستان"},
            47: {id: 47, city_id: 4, name: "آوج"},
            48: {id: 48, city_id: 5, name: "محمدیه"},
            49: {id: 49, city_id: 6, name: "مهرگان"},
            50: {id: 50, city_id: 7, name: "شال"},
            51: {id: 51, city_id: 8, name: "شریفیه"},
            52: {id: 52, city_id: 9, name: "ضیاآباد"},
            53: {id: 53, city_id: 10, name: "آبگرم"},
            54: {id: 54, city_id: 11, name: "دانسفهان"},
            55: {id: 55, city_id: 12, name: "بیدستان"},
        },
        isLoading: false,
        originalEstates: [],
        filterApplied: undefined,
        currentPage: 0,
        types: {
            1: {id: 1, name: "آپارتمان"},
            2: {id: 2, name: "مغازه/تجاری"},
            3: {id: 3, name: "اداری"},
            4: {id: 4, name: "زمین/کلنگی"},
            5: {id: 5, name: "خانه/ویلا"},
            6: {id: 6, name: "باغ و کشاورزی"},
            7: {id: 7, name: "کارخانه/کارگاه"}
        }
    };


    constructor(props) {
        super(props);
        this.getServerData = this.getServerData.bind(this);
    }

    async getServerData() {
        // let city = this.props.match.params.city;
        //  await this.props.filterEstates(city);
        const storageCurrentPage = Number(window.localStorage.getItem('currentPage'));
        console.log('storageCurrentPageReceive', storageCurrentPage);
        const currentPage = storageCurrentPage ? storageCurrentPage : this.state.currentPage;
        const firstItem = currentPage * 4;
        const endItem = firstItem + 4;
        const {estates} = this.props;
        const totalItemsCount = estates.length;
        await this.setState({
            originalEstates: estates,
            estates: estates.slice(0, endItem),
            totalItemsCount,
            hasMorEstate: endItem < totalItemsCount,
            currentPage,
        });
    }

    componentDidMount() {
        this.props.changeDisabledCity(false);
        console.log('componentDidMount,this.props.estates:', this.props.estates);
        if (this.props.estates.length > 0) {
            this.getServerData();
        }

        // this.getServerData();
        // const url =`api/estates/`;
        // // fetch initial data in this function here
        // this.setState(() => ({isLoading: true}));
        // axios.get(url,
        //     {
        //         params:{'city': city
        //         }
        //     }).then((response) => (this.setState(
        //         {
        //             originalEstates: response.data,
        //              estates: response.data,
        //             totalItemsCount: response.data.length,
        //             isLoading: false,
        //             activePage: 1
        //         }
        //     )));
    }

    componentWillReceiveProps(nextProps) {
        window.scrollTo(0, 100);
        window.localStorage.removeItem('currentPage');
        //     //اینو برای اتولود اضافه کردم
        const {estates} = nextProps;
        const totalItemsCount = estates.length;
        this.setState({
            originalEstates: estates,
            estates: estates.slice(0, 4),
            totalItemsCount,
            hasMorEstates: totalItemsCount > 4,
        });

        console.log("componentWillReceivePropsSearchResulrt", nextProps.estates);

        //
        //     console.log('componentWillReceivePropsSearchResulrtNextProps',)
        //     let currentCity = this.props.match.params.city;
        //     // let currentCategory = this.props.match.params.category;
        //     // let currentQuery = this.props.match.params.query;
        //
        //     let newCity = nextProps.match.params.city;
        //     // let newCategory = nextProps.match.params.category;
        //     // let newQuery = nextProps.match.params.query;
        //     // if((currentCategory !== newCategory) || (currentQuery !== newQuery)){
        //     // this.props.filterEstates(newCity);
        //
        //     if ((currentCity !== newCity)) {
        //
        //         this.props.filterEstates(newCity);
        //         this.setState({
        //             estates: this.props.estates.data
        //         })
        //         // const url =`api/estates/`;
        //         // this.setState(() => ({isLoading: true}));
        //         // axios.get(url,{params:{'city':newCity}}).then((response) => (this.setState(
        //         //     {
        //         //         originalEstates: response.data,
        //         //          estates: response.data,
        //         //         totalItemsCount: response.data.length,
        //         //         isLoading: false,
        //         //         activePage: 1
        //         //     }
        //         //     )));
        //     }
    }

    fetchMoreData = (page) => {
        // if (!this.state.isLoading) {
        //     this.setState({
        //         isLoading: true
        //     });
            const currentPage = this.state.currentPage + 1;
            const firstItem = currentPage * 4;
            const endItem = firstItem + 4;
            this.setState({
                estates: this.state.originalEstates.slice(0, endItem),
                currentPage,
                hasMorEstate: (endItem < this.state.totalItemsCount)
            }, () => {
                console.log('handel currentPage LoadMore', currentPage, firstItem, endItem, 'hasMorEstate:', this.state.hasMorEstate, 'totalItemsCount:', this.state.totalItemsCount);
            })
        // }
        // if (!this.state.isLoading) {
        //     this.setState({isLoading: true});
        //     // let city = this.props.match.params.city;
        //     const nextUrl = this.props.estates.next_page_url;
        //     const {last_page, current_page} = this.props.estates;
        //
        //     //  در این قسمت concat داده های قبلی با داده های جدید رو باید انجام بدم که قعلا به جای concat جایگرین می شه.
        //     this.setState(prevState => ({
        //         estates: this.props.estates.data,
        //     }), () => this.getNextPage(nextUrl));
        //     console.log('estates:', this.props.estates, 'url:', nextUrl, 'page:', page, 'last_page:', last_page, 'current_page:', current_page);
        //
        // };
    };
    getNextPage = (nextUrl) => {
        if (nextUrl) {
            this.props.filterEstates(nextUrl);
        } else {
            this.setState({
                hasMorEstate: false,
            })
        }
    };

    componentWillUnmount() {
        window.localStorage.setItem('currentPage', this.state.currentPage);
    }

    render() {
        const {estates} = this.state;
        console.log('render,this.props.estates', estates)
        const {regions, types,} = this.state;
        if (this.state.filterApplied) {
            <LoadingScreen>
                <span>در حال دریافت ملک ها...</span>
            </LoadingScreen>
        }
        if (estates && estates.length > 0) {
            return (
                <InfiniteScroll
                    dataLength={this.state.estates.length}
                    next={this.fetchMoreData}
                    hasMore={this.state.hasMorEstate}
                    loader={<h4>Loading...</h4>}
                    endMessage={
                        <p style={{ textAlign: "center" }}>
                            <b>شما همه ملکها را دیده اید.</b>
                        </p>
                    }
                >
                    {estates.map((estate) => (
                        <div key={estate.id} className=" col-sm-6 col-xs-12 content-box col-xs-no-padding">
                            <GridEstate estate={estate}/>
                        </div>
                    ))}
                </InfiniteScroll>

            );
        } else {
            return (
                <Grid className={"minimum-height"}>
                    <Row className={"star-rating-div"}>
                        <Col lg={8} md={8} xs={11}>
                            <Panel bsStyle="warning">
                                <Panel.Heading>
                                    <Panel.Title componentClass="h3">ملکی یافت نشد.</Panel.Title>
                                </Panel.Heading>
                                <Panel.Body>
                                    برای این جستجو ملکی یافت نشد. لطفا جستجو را تغییر دهید.
                                </Panel.Body>
                            </Panel>
                        </Col>
                    </Row>
                </Grid>
            );
        }
    }
}

const mapStateToProps = (state) => ({
    estates: state.estates,
});
export default withRouter(connect(mapStateToProps, {changeDisabledCity, filterEstates})(SearchResultsComponent));