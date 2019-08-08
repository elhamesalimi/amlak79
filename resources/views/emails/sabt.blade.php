
                        <table style="font-size:13px;width:100%;max-width:600px;background-color:white;border-color:#e0e0e0;border-width:4px;border-style:solid;text-align: center;

padding: 20px;" height="200" cellspacing="0" cellpadding="0" border="0">
                            <tbody>
                            <tr >
                                <td > &nbsp; </td>
                            </tr><tr>
                                <td >
                                    <p>
                                        لحظاتی پیش یک آگهی‌ با این نشانی ایمیل بر روی <em>املاک 79</em> ثبت شده است.
                                        آدرس ایمیل شما تأیید شده است. تأیید نهایی آگهی ممکن است کمی زمان ببرد.<br>
                                        شما می‌توانید با فشار دکمهٔ پایین آگهی خود را مدیریت نمایید.
                                    </p>
                                </td>
                            </tr><tr >
                                <td > &nbsp; </td>
                            </tr><tr>
                                <td >
                                    <h1 >{{$estate->title}}</h1>

                                    <div >

                                        <p>
                                            <a href="{{url('/manage',['code'=>$estate->code , 'email'=>$estate->type_email])}}" style="color:#ffffff;height:32px;margin:5px 5px 0 0;padding:0 22px;background-color:red" target="_blank">
	                                    مدیریت آگهی
                                            </a>
                                        </p>
                                    </div>

                                </td>
                            </tr><tr >
                                <td > &nbsp; </td>
                            </tr></tbody>
                        </table>
