import React, { Component } from "react";
import Slider from "react-slick";
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";
export default class AsNavFor extends Component {
    constructor(props) {
        super(props);
        this.state = {
            nav1: null,
            nav2: null
        };
    }

    componentDidMount() {
        this.setState({
            nav1: this.slider1,
            nav2: this.slider2
        });
    }

    render() {
        return (
            <div>
                <h2>Slider Syncing (AsNavFor)</h2>
                <Slider
                    asNavFor={this.state.nav2}
                    fade={true}
                    ref={slider => (this.slider1 = slider)}

                >
                    <div>
                        <img src="/public_data/images/thumbs160x150/3AO9JMYY2hIj8ZbiEWLwuIfLq.jpg" alt=""/>
                    </div>
                    <div>
                        <h3>2</h3>
                    </div>
                    <div>
                        <h3>3</h3>
                    </div>

                </Slider>
                <div className="second-slider">
                    <Slider
                        asNavFor={this.state.nav1}
                        ref={slider => (this.slider2 = slider)}
                        slidesToShow={3}
                        focusOnSelect={true}
                        autoplay={true}
                    >

                    <div>

                <img src="/public_data/images/thumbs160x150/3AO9JMYY2hIj8ZbiEWLwuIfLq.jpg" alt=""/>
                    </div>
                    <div>
                        <h3>2</h3>
                    </div>
                    <div>
                        <h3>3</h3>
                    </div>

                </Slider>
                </div>

            </div>
        );
    }
}