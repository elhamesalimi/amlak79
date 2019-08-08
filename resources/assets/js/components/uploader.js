
import React, {Component, Fragment} from 'react';
import Dropzone from 'react-dropzone';
import {post} from 'axios';

export default class Uploader extends Component {

    constructor(props){
        super(props);
        this.state = {
            images : [],
            progress : 0,
            uploading : true,
            supported_mime : [
                'image/jpeg',
                'image/png',
            ]
        }
    }
    onDrop(images){
        console.log('images',images);
        this.setState({
            images: images.map(image => Object.assign(image, {
                preview: URL.createObjectURL(image)
            }))
        });
        //  this.setState({
            // images : this.state.images.concat([...images])
        // });
    }

    onDropRejected(images){
        if(images.length){
            alert('Please upload valid image files. Supported extension JPEG and PNG', 'Invalid MIME type');
            // toastr.error('Please upload valid image files. Supported extension JPEG and PNG', 'Invalid MIME type')
        }
    }
    uploadFiles(){
        let images = this.state.images,
            config = { headers: { 'Content-Type': 'multipart/form-data' } },
            total_files = this.state.images.length,
            uploaded = 0;

        this.setState({
            uploading : true
        });

        images.map((image) => {
            let formData = new FormData();
            formData.append("file", image);

            post("/photos", formData, config).then(response => {
                const done = response.data;
                if(done){
                    this.removeDroppedFile(image.preview);
                    // this.calculateProgress(total_files, ++uploaded);
                }
            });
        });
    }
    removeDroppedFile(preview, e = null){
        this.setState({
            images : this.state.images.filter((image) => {
                return image.preview !== preview
            })
        })
    }
    componentWillUnmount() {
        // Make sure to revoke the data uris to avoid memory leaks
        this.state.files.forEach(file => URL.revokeObjectURL(file.preview))
    }

    render() {
        return (
            <div className="uploader">
                <div className="text-center">
                    <Dropzone
                        onDropAccepted={this.onDrop.bind(this)}
                        onDropRejected={this.onDropRejected.bind(this)}
                        className="btn btn-dark"
                        accept=".bmp,.png,.jpeg,.jpg"

                    >
                        {({getRootProps, getInputProps}) => (
                            <div  className="dropzone image-uploader" {...getRootProps()}>
                                <i aria-hidden="true" className="fa fa-2x fa-camera-retro"></i>

                                <input {...getInputProps()} />
                            </div>
                        )}
                    </Dropzone>

                    {this.state.images.length > 0 &&
                    <button
                        className="btn btn-dark uploadBtn"
                        onClick={this.uploadFiles.bind(this)}
                    >
                        Upload
                    </button>
                    }

                </div>

                {this.state.images.length ?
                    <Fragment>
                        {this.state.uploading &&
                        <div className="progress">
                            <div
                                className="progress-bar"
                                role="progressbar"
                                style={{width : this.state.progress}}
                                aria-valuenow={this.state.progress}
                                aria-valuemin="0"
                                aria-valuemax="100"/>
                        </div>
                        }

                        <div className="images">
                        {
                            this.state.images.map((file,index) =>
                                <div key={file.preview} className="image">
                                        <span
                                            className="close"
                                            onClick={this.removeDroppedFile.bind(this, file.preview)}
                                        >X</span>
                                    <img src={file.preview} alt=""/>
                                </div>
                            )
                        }
                    </div>
                    </Fragment>
                    :
                    <div className="no-images">
                        <h5 className="text-center">
                            Selected images will appear here
                        </h5>
                    </div>
                }
            </div>

        );
    }
}