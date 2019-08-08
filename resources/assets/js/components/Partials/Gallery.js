import React from 'react';
import ImageGallery from 'react-image-gallery';
import "../../../../../node_modules/react-image-gallery/styles/css/image-gallery.css";
export default class Gallery extends React.Component {
state = {
    images: [],
}
    componentDidMount(){
        let getUrl = window.location;
        if (this.props.images.length) {

        this.setState({
            images: this.state.images.concat(
                this.props.images.map(file => Object.assign(file, {
                    original:`${getUrl .protocol}//${getUrl.host}/public_data/images/thumbs600x450/${file.uri}`,
                    thumbnail:`${getUrl .protocol}//${getUrl.host}/public_data/images/thumbs110x62/${file.uri}`,
                }))
            )})
        }
    }
    render() {
    const {images} = this.state;
        return (
            <div>
                {images &&
                < ImageGallery autoPlay={true} items={this.state.images} slideInterval={10000} showPlayButton={false} lazyLoad={true} showNav={false} />
                }
                </div>
        );
    }

}