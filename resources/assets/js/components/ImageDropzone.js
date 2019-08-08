import React from 'react'
import classNames from 'classnames'
import Dropzone from 'react-dropzone'
import axios from "axios";
import Compressor from 'compressorjs';

const imageMaxSize = 10 * 1024 * 1024;
const upload = [];

class ImageDropzone extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            images: [],
            total_images: 0,
            uploading: false,
            showErrorMessage: false,
            errorMessage: '',
            uploaded: [],
        };
        this.uplaodFiles = this.props.uplaodFiles.bind(this);
    }

    afterImageUpdate = () => {
        console.log('after uploaded:', this.state.uploaded);

        const imagesIds = this.state.uploaded.map(image => (image.id));
        console.log('imagesIds:' + imagesIds);
        this.uplaodFiles(imagesIds);
        let countImage = this.state.images.length;
        let showErrorMessage = false;
        let errorMessage = '';
        if (countImage > 5) {
            for (let i = 5; i < countImage; i++) {
                countImage--;
                showErrorMessage = true;
                errorMessage = 'تعداد عکس های انتخاب شده نباید بیشتر از 5 عکس باشد';
            }
        }
        this.setState({
            showErrorMessage,
            errorMessage
        })
    };

    componentWillReceiveProps(nextProps) {

    }

    componentDidMount() {
        let getUrl = window.location;

        if (this.props.images.length) {
            console.log('this.props.images:', this.props.images);
            this.setState({
                images: this.state.images.concat(
                    this.props.images.map(file => Object.assign(file, {
                        ...file,
                        preview: `${getUrl.protocol}//${getUrl.host}/public_data/images/thumbs160x160/${file.uri}`,
                    }))
                ),
                uploaded: this.state.uploaded.concat(
                    this.props.images.map(file => Object.assign(file, {
                        ...file.id,
                        preview: `${getUrl.protocol}//${getUrl.host}/public_data/images/thumbs160x160/${file.uri}`,
                    }))
                )
            },);
        }
    }

    onDrop = (images, regiectFiles) => {
        let count = this.state.images.length;
        let config = {headers: {'Content-Type': 'multipart/form-data'}},
            total_files = images.length;
        this.setState({
            uploading: true
        });
        const limit = 5 - count;
        let done;
        if (images.length) {
            let files = images.map((file, i) => {
                const preview = URL.createObjectURL(file);
                let uploaded = this.state.uploaded;
                if (i < limit) {
                    new Compressor(file, {
                        quality: 0.8,
                        maxWidth: 700,
                        checkOrientation: false,
                        convertSize: 0,
                        success: (result) => {
                            const formData = new FormData();
                            formData.append('file', result, result.name);
                            axios.post("/photos", formData, config).then(response => {
                                done = response.data;
                                console.log('this:', this);
                                if (done) {
                                    uploaded.push({id: done, preview});
                                    this.setState({
                                        uploaded
                                    }, this.afterImageUpdate)
                                    // this.removeDroppedFile(image.preview);
                                    // this.calculateProgress(total_files, ++uploaded);
                                }
                            });
                        }
                    });
                    return Object.assign(file, {
                        ...file,
                        preview,
                    });
                }
                console.log('after uploaded:', uploaded);

            });
            this.setState({
                images: this.state.images.concat(
                    files.filter((image) => {
                        return image != null
                    })
                )
            });
        }
        let maxImage = false;
        const countImages = this.state.images.length + images.length;
        if (countImages > 5) {
            maxImage = true;
        }
        let isRejectedFile = false;
        if (regiectFiles && regiectFiles.length > 0) {
            isRejectedFile = true;
            // alert('Please upload valid image images. Supported extension JPEG and PNG', 'Invalid MIME type');
        }
        let showErrorMessage = false;
        let errorMessage = '';
        if (isRejectedFile || maxImage) {
            showErrorMessage = true;
            errorMessage = isRejectedFile ? 'سایز عکس بیشتر از 10 مگ است یا پسوند عکس هایی که بارگذاری نشد صحیح نمی باشد.' : '';
            errorMessage += (isRejectedFile && maxImage) ? '\n' : '';
            errorMessage += maxImage ? 'تعداد عکس های انتخاب شده بیشتر از 5 عکس می باشد' : '';
        }
        this.setState({
            showErrorMessage,
            errorMessage
        });
    }

    removeDroppedFile(preview, e = null) {

        this.setState({
            images: this.state.images.filter((image) => {
                return image.preview !== preview
            }),
            uploaded: this.state.uploaded.filter((image) => {
                return image.preview !== preview
            })
        }, this.afterImageUpdate);

    }

    componentWillUnmount() {
        // Make sure to revoke the data uris to avoid memory leaks
        this.state.images.forEach(file => URL.revokeObjectURL(file.preview))
    }

    render() {
        const {images} = this.state;

        const thumbs = images.map(file => (
            <div className="thumb" key={file.name}>
                <div className="thumb-inner">
                    <img
                        src={file.preview}
                        className="img"
                    />{this.state.uploaded.length > 0 && this.state.uploaded.map(image => {
                    if (image.preview === file.preview) {
                        return (
                            <button type="button" onClick={this.removeDroppedFile.bind(this, file.preview)}
                                    className="delete-btn">
                                <i aria-hidden="true" className="fa fa-trash-o"></i>
                                {/*<i aria-hidden="true" className="trash outline icon"></i>*/}
                            </button>
                        )
                    }

                })
                }
                {this.state.uploaded.findIndex(item => item.preview===file.preview) === -1 &&
                    <div className="image_preloader"><i className="fa fa-spinner fa-pulse"></i></div>
                }
                </div>
            </div>
        ));

        return (
            <div>
                <div style={{display: 'inline-flex', float: 'right'}}>
                    <aside className="thumbs-container">
                        {thumbs}

                        <Dropzone maxSize={imageMaxSize}
                                  accept=".bmp,.png,.jpeg,.jpg"
                            // onDrop={(images)=>{this.props.onDrop(images,rejectedImages)}}
                                  onDrop={this.onDrop.bind(this)}
                        >
                            {({getRootProps, getInputProps}) => (
                                <div className="dropzone image-uploader" {...getRootProps()}>
                                    <i aria-hidden="true" className="fa fa-2x fa-camera-retro"></i>
                                    <input ref={"file"} name={"file"} {...getInputProps()} />
                                </div>
                            )}
                        </Dropzone>
                    </aside>
                </div>
                <div className="clearfix"></div>
                {this.state.showErrorMessage && (
                    <div style={{marginTop: '10px'}} className={"alert alert-danger"}>
                        {this.state.errorMessage}
                    </div>
                )}
            </div>
        );
    }
}

export default ImageDropzone
