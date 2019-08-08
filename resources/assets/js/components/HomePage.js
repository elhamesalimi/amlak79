import React from 'react';
import {Grid, Col, Row, Carousel} from 'react-bootstrap';
import {Router, withRouter} from "react-router-dom";
import SearchResultsComponent from "./SearchResultsComponent";
import AdvancedFilters from "./AdvancedFilters";
import {changeSelectedCity} from '../actions/city';
import {connect} from "react-redux";
import configureStore from '../store/configureStore';
import {CHANGE_SELECTED_CITY} from "../api/strings";
import Footer from "./Footer";

class HomePage extends React.Component {

    componentDidMount() {
        const store = configureStore();

        const {city} = this.props.match.params;

        this.props.changeSelectedCity(city);
    }

    render() {
        return (
            <div>
                <div className={"row"} id="content" style={{marginBottom: '10px'}}>
                    <div className={"col-xs-12"}>
                        <div className="filter col-xs-12 col-sm-4 " style={{padding: '0px'}}>
                            <AdvancedFilters/>
                        </div>
                        <div className="search-result col-xs-12 col-sm-8  nopadding" style={{float: 'left'}}>
                            <SearchResultsComponent  {...this.props}/>
                        </div>
                    </div>
                </div>
                <Footer/>
            </div>

        );
    }
}

export default connect(null, {changeSelectedCity})(HomePage);
