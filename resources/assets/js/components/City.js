import React , {Component} from 'react';
import {withRouter} from "react-router-dom";
import axios from 'axios';
import LoadingScreen from "./LoadingScreen";
class City extends Component{

    state = {
            estates: [],
            isLoading: false,
        }


    componentDidMount(){
        let selectedCity = this.props.match.params.city;

        // fetch initial data in this function here
        this.setState(() => ({isLoading: true}));
        axios.get(`api/estates/${selectedCity}`).then((response) => (this.setState(
            {
                estates: response.data,
                isLoading: false,
            }
        )));
    }

    componentWillReceiveProps(nextProps){
        let currentCity = this.props.match.params.city;
        let newCity = nextProps.match.params.city;
        console.log(nextProps);
        console.log(newCity);

        if((currentCity !== newCity) ){
            console.log(newCity);

            const url = `api/estates/${newCity}`;
            this.setState(() => ({isLoading: true}));
            axios.get(url).then((response) => (this.setState(
                {
                    estates: response.data,
                    isLoading: false,
                }
            )));
        }
    }

    render(){
        if(this.state.isLoading){
            return <LoadingScreen/>
        }


        return (
            <div>{this.state.estates.map(estate=><h1 key={estate.id}>{estate.price}</h1>)}</div>
        )
    }
}
export default withRouter(City);