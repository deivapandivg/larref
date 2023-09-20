<x-app-layout>
    <style type="text/css">
        .copy-click {
            position: relative;
            padding-bottom: 2px;
            text-decoration: none;
            cursor: copy;
            color: #484848;
            /*border-bottom: 1px dashed #767676;*/
            transition: background-color calc(var(--duration) * 2) var(--ease);
        }

        .copy-click:after {
            content: attr(data-tooltip-text);
            position: absolute;
            bottom: calc(100% + 6px);
            left: 50%;
            padding: 8px 16px;
            white-space: nowrap;
            background-color: white;
            border-radius: 4px;
            box-shadow: 0 0 0 -12px rgba(0, 0, 0, 0);
            pointer-events: none;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            opacity: 0;
            -webkit-transform: translate(-50%, 12px);
            transform: translate(-50%, 12px);
            transition: box-shadow calc(var(--duration) / 1.5) var(--bounce), opacity calc(var(--duration) / 1.5) var(--bounce), -webkit-transform calc(var(--duration) / 1.5) var(--bounce);
            transition: box-shadow calc(var(--duration) / 1.5) var(--bounce), opacity calc(var(--duration) / 1.5) var(--bounce), transform calc(var(--duration) / 1.5) var(--bounce);
            transition: box-shadow calc(var(--duration) / 1.5) var(--bounce), opacity calc(var(--duration) / 1.5) var(--bounce), transform calc(var(--duration) / 1.5) var(--bounce), -webkit-transform calc(var(--duration) / 1.5) var(--bounce);
        }

        .copy-click.is-hovered:after {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            opacity: 1;
            -webkit-transform: translate(-50%, 0);
            transform: translate(-50%, 0);
            transition-timing-function: var(--ease);
        }

        .copy-click.is-copied {
            background-color: yellow;
        }

        .copy-click.is-copied:after {
            content: attr(data-tooltip-text-copied);
        }
    </style>
    <section id="tabs-with-icons">
        <div class="row match-height">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Sms
                            <ol class="breadcrumb mt-0">
                                <li class="breadcrumb-item active "><span class="btn btn-sm p-0 text-primary"
                                        onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go
                                        Back</span>
                                </li>
                            </ol>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form class="form form-horizontal" method="post" enctype="multipart/form-data"
                            action="{{ route('sms_submit') }}">
                            @csrf
                            <div class="form-body">
                                <div class="row container">
                                    <div class="col-lg-9 bordered border-right">
                                        <div class="row container">
                                            <div class="col-lg-8">
                                                <input type="hidden" name="template_id"
                                                    value="{{ $sms->template_id }}">
                                                <div class="form-group">
                                                    <label class="label-control"><b>Template Name <sup
                                                                class="text-danger" style="font-size: 13px;">*</sup>
                                                            :</b></label>
                                                    <input type="text" id="Template Name" required
                                                        name="template_name"class="name form-control"
                                                        placeholder="Template Name" value="{{ $sms->template_name }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row container">
                                            <div class="col-lg-8">
                                                <div class="form-group">
                                                    <label class="label-control"><b>Sms Content: <sup
                                                                class="text-danger" style="font-size: 13px;">*</sup>
                                                            :</b></label>
                                                    <textarea rows="6" cols="50" class="form-control border-primary" required placeholder="Enter Message"
                                                        name="content">{{ $sms->content }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                            </div>
                                        </div>
                                        <style type="text/css">
                                            .VGLabel {
                                                min-width: 130px;
                                            }
                                        </style>

                                    </div>
                                    <div class="col-lg-3">
                                        <label for="userinput4"><b><u> Quick Content</u></b></label><br>
                                        <a href="" class="copy-click" data-tooltip-text="Click To Copy"
                                            data-tooltip-text-copied="✔ Copied">{{ 'LeadName' }}</a><br>
                                        <a href="" class="copy-click" data-tooltip-text="Click To Copy"
                                            data-tooltip-text-copied="✔ Copied">{{ 'MobileNumber' }}</a><br>
                                        <a href="" class="copy-click" data-tooltip-text="Click To Copy"
                                            data-tooltip-text-copied="✔ Copied">{{ 'LeadMail' }}</a><br>
                                        <a href="" class="copy-click" data-tooltip-text="Click To Copy"
                                            data-tooltip-text-copied="✔ Copied">{{ 'LeadOwnerName' }}</a><br>
                                        <a href="" class="copy-click" data-tooltip-text="Click To Copy"
                                            data-tooltip-text-copied="✔ Copied">{{ 'LeadOwnerNumber' }}</a><br>
                                        <a href="" class="copy-click" data-tooltip-text="Click To Copy"
                                            data-tooltip-text-copied="✔ Copied">{{ 'LeadOwnerMail' }}</a><br>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions right">
                                <a href="{{ route('sms') }}">
                                    <button type="button" class="btn btn-danger mr-1">
                                        <i class="fa fa-times"></i> Cancel
                                    </button>
                                </a>
                                <button type="submit" name="submit" id="button" class="btn btn-primary btn-md">
                                    <i class="fa fa-check"></i> Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-slot name="page_level_scripts">
        <script type="text/javascript">
            const links = document.querySelectorAll('.copy-click');
            const cls = {
                copied: 'is-copied',
                hover: 'is-hovered'
            };


            const copyToClipboard = str => {
                const el = document.createElement('input');
                str.dataset.copyString ? el.value = str.dataset.copyString : el.value = str.text;
                el.setAttribute('readonly', '');
                el.style.position = 'absolute';
                el.style.opacity = 0;
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
            };

            const clickInteraction = e => {
                e.preventDefault();
                copyToClipboard(e.target);
                e.target.classList.add(cls.copied);
                setTimeout(() => e.target.classList.remove(cls.copied), 1000);
                setTimeout(() => e.target.classList.remove(cls.hover), 700);
            };

            Array.from(links).forEach(link => {
                link.addEventListener('click', e => clickInteraction(e));
                link.addEventListener('keypress', e => {
                    if (e.keyCode === 13) clickInteraction(e);
                });
                link.addEventListener('mouseover', e => e.target.classList.add(cls.hover));
                link.addEventListener('mouseleave', e => {
                    if (!e.target.classList.contains(cls.copied)) {
                        e.target.classList.remove(cls.hover);
                    }
                });
            });
        </script>
        <style type="text/css">
            #search {
                margin-top: 5px;
                margin-left: 9px;
                font-size: 33px;
            }

            #search:hover {
                cursor: pointer;
            }
        </style>
    </x-slot>
</x-app-layout>
