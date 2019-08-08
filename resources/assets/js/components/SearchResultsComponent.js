import React from 'react';
import InfiniteScroll from 'react-infinite-scroll-component';
import {Grid, Row, Col, Panel} from 'react-bootstrap';
import LoadingScreen from "../components/LoadingScreen";
import {withRouter} from "react-router-dom";
import {
     NEW, PRICE_HIGH_TO_LOW, PRICE_LOW_TO_HIGH, RATINGS,
} from "../api/strings";
import {connect} from "react-redux";
import {filterEstates} from '../actions/estates';
import GridEstate from "./GridEstate";
import {changeDisabledCity} from "../actions/city";

const countOfItem = 20;
const style = {
    fontSize: '40px',
    color: '#aab0b6',
    paddingRight: '20px'
};
const visitStyle = {
    clear: 'both',
    textAlign: 'center',

};
const result = {
    marginTop: '-85px',
    paddingTop: '85px',
};


class SearchResultsComponent extends React.Component {

    state = {
        sortBySelected: NEW,
        sortByOptions: [PRICE_LOW_TO_HIGH, PRICE_HIGH_TO_LOW, RATINGS],
        activePage: 1,
        totalItemsCount: 0,
        advancedFilterModalShow: false,
        estates: [],
        hasMorEstate: true,
        isLoading: true,
        originalEstates: [],
        filterApplied: undefined,
        currentPage: 0,
    };

    constructor(props) {
        super(props);
        this.myRef = React.createRef();
        this.getServerData = this.getServerData.bind(this);
    }

    async getServerData() {
        // let city = this.props.match.params.city;
        //  await this.props.filterEstates(city);
        const storageCurrentPage = Number(window.localStorage.getItem('currentPage'));
        const currentPage = storageCurrentPage ? storageCurrentPage : this.state.currentPage;
        const firstItem = currentPage * countOfItem;
        const endItem = firstItem + countOfItem;
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
        window.scrollTo(0, 0);
        this.props.changeDisabledCity(false);
        if (this.props.estates.length > 0) {
            this.getServerData();
        }
        this.setState({isLoading: false});
    }
    componentWillReceiveProps(nextProps) {
        console.log('componentWillReceivePropsSearchResult');
        window.localStorage.removeItem('currentPage');
        //     //اینو برای اتولود اضافه کردم
        const {estates} = nextProps;
        const totalItemsCount = estates.length;
        this.setState({
            originalEstates: estates,
            estates: estates.slice(0, countOfItem),
            totalItemsCount,
            hasMorEstate: totalItemsCount > countOfItem,
            isLoading: false
        });
        this.myRef.current.scrollIntoView({behavior: 'smooth'});
    }

    fetchMoreEstate = (page) => {
        const currentPage = this.state.currentPage + 1;
        const firstItem = currentPage * countOfItem;
        const endItem = firstItem + countOfItem;
        this.setState({
            estates: this.state.originalEstates.slice(0, endItem),
            currentPage,
            hasMorEstate: (endItem < this.state.totalItemsCount)
        }, () => {
            console.log('handel currentPage LoadMore', currentPage, firstItem, endItem, 'hasMorEstate:', this.state.hasMorEstate, 'totalItemsCount:', this.state.totalItemsCount);
        })
    };

    componentWillUnmount() {
        window.localStorage.setItem('currentPage', this.state.currentPage);
    }

    handleClick = () => {
        window.scrollTo(0, 0);
    }

    render() {
        const {estates, isLoading} = this.state;
        return (
            <div ref={this.myRef} id="result" style={result}>
                {isLoading ?
                    <LoadingScreen>
                        <span>در حال دریافت ملک ها...</span>
                    </LoadingScreen>
                    :
                    (estates && estates.length > 0) ?
                        <div>
                            <InfiniteScroll
                                dataLength={this.state.estates.length}
                                next={this.fetchMoreEstate}
                                hasMore={this.state.hasMorEstate}
                                loader={<LoadingScreen>
                                    <span> ملک های بیشتر...</span>
                                </LoadingScreen>}
                                endMessage={
                                    <span style={style} onClick={this.handleClick}>
                                <i className={"fa fa-arrow-circle-up 2w"}></i>
                            </span>
                                }
                            >
                                {estates.map((estate) => (
                                    <div key={estate.id} className="col-sm-6 col-xs-12 content-box col-xs-no-padding">
                                        <GridEstate estate={estate}/>
                                    </div>
                                ))}
                            </InfiniteScroll>
                        </div>

                        : <div id={"result"}>
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
                        </div>
                }
            </div>
        );
    }
}

const
    mapStateToProps = (state) => ({
        estates: state.estates,
    });
export default withRouter(connect(mapStateToProps, {changeDisabledCity, filterEstates})(SearchResultsComponent));