<html lang="ko" class=" ">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>회원가입 | 헬프샵 - 쇼핑몰 통합관리</title>
    <link rel="canonical" href="https://www.sellertool.io">
    <meta name="description" content="마진율 계산기 부터 재고관리 까지 오픈마켓 통합 관리 솔루션 셀러툴!">
    <meta name="keywords" content="셀러툴, 마진율 계산기, 엑셀 변환기, 스마트스토어, 내 상품 순위, 온라인 커머스, 3PL, WMS, 선입선출, 주문관리">
    <meta property="og:type" content="website">
    <meta property="og:title" content="셀러툴 - 쇼핑몰 통합관리">
    <meta property="og:description" content="마진율 계산기 부터 재고관리 까지 오픈마켓 관리 솔루션 셀러툴!">
    <meta property="og:site_name" content="셀러툴 - 쇼핑몰 통합관리">
    <meta property="og:url" content="https://www.sellertool.io">
    <meta property="og:locale" content="ko_KR">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css" data-n-g="">
    <link rel="stylesheet" type="text/css" href="./css/common.css" data-n-g="">
    <link rel="stylesheet" type="text/css" href="./css/signup.css" data-n-g=""><noscript data-n-css=""></noscript>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    <script src="./js/common.js"></script>
    <style>
        .error { 
            color: red;
            font-size: 13px;
            display: inline-block;
            padding-top: 3px;
        }
    </style>
</head>

<body>
    <div id="__next" data-reactroot="">
        <div class="signup_Container">
            <div class="form_Container">
                <div class="form_LogoBox">
                    <a href="/">
                        <span style="box-sizing: border-box; display: block; overflow: hidden; width: initial; height: initial; background: none; opacity: 1; border: 0px; margin: 0px; padding: 0px; position: relative;">
                            <span style="box-sizing: border-box; display: block; width: initial; height: initial; background: none; opacity: 1; border: 0px; margin: 0px; padding: 100% 0px 0px;"></span>
                            <img alt="image" sizes="100vw" src="/images/logo/logo1.png?q=75" decoding="async" data-nimg="responsive" style="position: absolute; inset: 0px; box-sizing: border-box; padding: 0px; border: none; margin: auto; display: block; width: 0px; height: 0px; min-width: 100%; max-width: 100%; min-height: 100%; max-height: 100%; object-fit: cover;">
                        </span>
                    </a>
                </div>
                <div class="form_Wrapper">
                    <div class="form_Header">
                        <div class="head-box">
                            <div class="title">회원가입</div>
                        </div>
                    </div>
                    <form class="form_FormGroup" id="registerForm" >
                        <input type="hidden" name="type" value="signup">
                        <div class="form_InputBox">
                            <div class="input-label" style="display: flex; align-items: center;">
                                <div style="margin-right: 10px;">아이디</div>
                            </div>
                            <input type="text" class="input-item" name="user_id" value="" id="user_id" required>
                            <span class="error" id="id_error"></span>
                        </div>
                        
                        <div class="form_InputBox">
                            <div class="input-label">
                                <div style="margin-right: 10px;">패스워드</div>
                            </div>
                            <input type="password" class="input-item" name="password" id="password" placeholder="영문, 숫자, 특수문자 혼합 8-50자" minlength="8" maxlength="50" required="" value="">
                        </div>
                        <div class="form_InputBox">
                            <div class="input-label">
                                <div style="margin-right: 10px;">패스워드 확인</div>
                            </div>
                            <input type="password" class="input-item" name="passwordChecker" id="passwordChecker" minlength="8" maxlength="50" required="" value="">
                            <span class="error" id="pw_error"></span>
                        </div>
                        <div class="form_InputBox">
                            <div class="input-label">
                                <div style="margin-right: 10px;">닉네임</div>
                            </div>
                            <input type="text" class="input-item" name="nickname" id="nickname" placeholder="2자 이상 15자 이하로 입력해 주세요." minlength="2" maxlength="15" required="" >
                            <span class="error" id="nick_error"></span>
                        </div>
                        <div class="form_InputBox">
                            <div class="input-label">
                                <div style="margin-right: 10px;">휴대전화</div>
                            </div>
                            <div style="display: flex; align-items: center;">
                                <input type="text" class="input-item" name="contact" id="contact" placeholder="ex)01012341234" maxlength="11" required="" value="">
                                <button type="button" class="validation-button" id="sendCodeBtn">인증번호 발송
                                    <div color="#e0e0e0" scale="2" class="Ripple_Container"></div>
                                </button>
                            </div>
                            <span id="phoneMsg" class="error"></span>
                        </div>
                        <div class="form_InputBox" id="verificationArea" style="display:none;">
                            <div class="d-flex">
                                <input type="text" class="input-item" id="verifyCode" placeholder="인증번호 입력" />
                                <button type="button" class="validation-button" id="checkCodeBtn">인증번호 확인</button>
                            </div>
                        </div>

                        <div class="consent_InputBox">
                            <div class="custom_CheckBox">
                                <label style="display: flex; align-items: center;">
                                    <input type="checkbox" name="totalTermYn" id="totalTermYn" class="CustomCheckboxV2__HiddenCheckbox-sc-17cmlwd-2 gommtu">
                                    <div class="CustomCheckboxV2__StyledCheckbox-sc-17cmlwd-3 ekwHHr checkbox-el">
                                        <span style="box-sizing: border-box; display: block; overflow: hidden; width: initial; height: initial; background: none; opacity: 1; border: 0px; margin: 0px; padding: 0px; position: relative;">
                                            <span style="box-sizing: border-box; display: block; width: initial; height: initial; background: none; opacity: 1; border: 0px; margin: 0px; padding: 100% 0px 0px;"></span>
                                            <img alt="check default white" sizes="100vw" srcset="/images/icon/check_default_ffffff.svg?q=75 640w, /images/icon/check_default_ffffff.svg?q=75 750w, /images/icon/check_default_ffffff.svg?q=75 828w, /images/icon/check_default_ffffff.svg?q=75 1080w, /images/icon/check_default_ffffff.svg?q=75 1200w, /images/icon/check_default_ffffff.svg?q=75 1920w, /images/icon/check_default_ffffff.svg?q=75 2048w, /images/icon/check_default_ffffff.svg?q=75 3840w" src="/images/icon/check_default_ffffff.svg?q=75" decoding="async" data-nimg="responsive" class="icon-el" style="position: absolute; inset: 0px; box-sizing: border-box; padding: 0px; border: none; margin: auto; display: block; width: 0px; height: 0px; min-width: 100%; max-width: 100%; min-height: 100%; max-height: 100%;">
                                        </span>
                                    </div>
                                    <span class="CustomCheckboxV2__Label-sc-17cmlwd-4 gpMmUy">
                                        <div class="label-el">전체동의</div>
                                    </span>
                                </label></div>
                        </div>
                        <div class="consent_InputBox">
                            <div class="custom_CheckBox d-flex">
                                <label style="display: flex; align-items: center;">
                                    <input name="serviceTermsYn" type="checkbox" class="CustomCheckboxV2__HiddenCheckbox-sc-17cmlwd-2 termChk">
                                    <div class="CustomCheckboxV2__StyledCheckbox-sc-17cmlwd-3 ekwHHr checkbox-el">
                                        <span style="box-sizing: border-box; display: block; overflow: hidden; width: initial; height: initial; background: none; opacity: 1; border: 0px; margin: 0px; padding: 0px; position: relative;">
                                            <span style="box-sizing: border-box; display: block; width: initial; height: initial; background: none; opacity: 1; border: 0px; margin: 0px; padding: 100% 0px 0px;"></span>
                                            <img alt="check default white" sizes="100vw" srcset="/images/icon/check_default_ffffff.svg?q=75 640w, /images/icon/check_default_ffffff.svg?q=75 750w, /images/icon/check_default_ffffff.svg?q=75 828w, /images/icon/check_default_ffffff.svg?q=75 1080w, /images/icon/check_default_ffffff.svg?q=75 1200w, /images/icon/check_default_ffffff.svg?q=75 1920w, /images/icon/check_default_ffffff.svg?q=75 2048w, /images/icon/check_default_ffffff.svg?q=75 3840w" src="/images/icon/check_default_ffffff.svg?q=75" decoding="async" data-nimg="responsive" class="icon-el" style="position: absolute; inset: 0px; box-sizing: border-box; padding: 0px; border: none; margin: auto; display: block; width: 0px; height: 0px; min-width: 100%; max-width: 100%; min-height: 100%; max-height: 100%;">
                                        </span>
                                    </div>
                                    <span class="CustomCheckboxV2__Label-sc-17cmlwd-4 gpMmUy">
                                        <div class="label-el">서비스 
                                            <a href="https://assets.sellertool.io/consent_form/service_terms_v1.html" target="_blank" rel="noopener noreferrer">
                                                <span class="label-link">이용약관</span>
                                            </a> 동의 (필수)
                                        </div>
                                    </span>
                                </label>
                                <button type="button" class="btn btn-link btn-sm" data-bs-toggle="modal" data-bs-target="#termsModal">
                                    보기
                                </button>
                            </div>
                        </div>
                        <div class="consent_InputBox">
                            <div class="custom_CheckBox d-flex">
                                <label style="display: flex; align-items: center;">
                                    <input name="privacyPolicyYn" type="checkbox" class="CustomCheckboxV2__HiddenCheckbox-sc-17cmlwd-2 termChk">
                                    <div class="CustomCheckboxV2__StyledCheckbox-sc-17cmlwd-3 ekwHHr checkbox-el">
                                        <span style="box-sizing: border-box; display: block; overflow: hidden; width: initial; height: initial; background: none; opacity: 1; border: 0px; margin: 0px; padding: 0px; position: relative;">
                                            <span style="box-sizing: border-box; display: block; width: initial; height: initial; background: none; opacity: 1; border: 0px; margin: 0px; padding: 100% 0px 0px;"></span>
                                            <img alt="check default white" sizes="100vw" srcset="/images/icon/check_default_ffffff.svg?q=75 640w, /images/icon/check_default_ffffff.svg?q=75 750w, /images/icon/check_default_ffffff.svg?q=75 828w, /images/icon/check_default_ffffff.svg?q=75 1080w, /images/icon/check_default_ffffff.svg?q=75 1200w, /images/icon/check_default_ffffff.svg?q=75 1920w, /images/icon/check_default_ffffff.svg?q=75 2048w, /images/icon/check_default_ffffff.svg?q=75 3840w" src="/images/icon/check_default_ffffff.svg?q=75" decoding="async" data-nimg="responsive" class="icon-el" style="position: absolute; inset: 0px; box-sizing: border-box; padding: 0px; border: none; margin: auto; display: block; width: 0px; height: 0px; min-width: 100%; max-width: 100%; min-height: 100%; max-height: 100%;">
                                        </span>
                                    </div>
                                    <span class="CustomCheckboxV2__Label-sc-17cmlwd-4 gpMmUy">
                                        <div class="label-el">서비스 
                                            <a class="label-link" href="https://assets.sellertool.io/consent_form/privacy_policy_v1.html" target="_blank" rel="noopener noreferrer">
                                                <span class="label-link">개인정보처리방침</span>
                                            </a> 동의 (필수)
                                        </div>
                                    </span>
                                </label>
                                <button type="button" class="btn btn-link btn-sm" data-bs-toggle="modal" data-bs-target="#privacyModal">
                                    보기
                                </button>
                            </div>
                        </div>
                        
                        <button type="submit" class="submit-button">
                            회원가입
                            <div color="#e0e0e0" scale="2" class="Ripple__RippleContainer-sc-1gpqkoy-0 "></div>
                        </button>
                    </form>
                    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="termsModalLabel">서비스 이용약관</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="닫기"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- 여기에 전체 약관 내용을 복사하여 붙여넣으세요 -->
                                    <pre class="text-wrap" style="white-space: pre-wrap;">제1조 (목적)
이 약관은 [서비스명] (이하 "회사")가 제공하는 주문 통합 관리, 매출/수익 분석, 출석 포인트 시스템 등 온라인 셀러를 위한 통합 서비스(이하 "서비스")의 이용조건 및 절차, 이용자와 회사의 권리·의무·책임사항을 규정함을 목적으로 합니다.

제2조 (정의)
1. "서비스"란 회사가 제공하는 웹 기반의 주문 통합 관리, 상품 키워드 분석, 출석체크 포인트 적립, 리포트 제공 등을 의미합니다.
2. "회원"이란 본 약관에 동의하고 서비스를 이용하는 자를 말합니다.
3. "콘텐츠"란 회원이 등록하거나 회사가 제공하는 데이터, 분석 자료, 통계 등을 의미합니다.

제3조 (약관의 효력 및 변경)
1. 본 약관은 서비스 화면에 게시하거나 기타 방법으로 공지함으로써 효력이 발생합니다.
2. 회사는 관련 법령을 위배하지 않는 범위에서 약관을 변경할 수 있으며, 변경된 약관은 서비스 내 공지합니다.
3. 회원은 변경된 약관에 동의하지 않을 경우 탈퇴할 수 있으며, 계속 이용 시 변경 약관에 동의한 것으로 간주합니다.

제4조 (서비스의 제공 및 변경)
1. 회사는 다음과 같은 서비스를 제공합니다.
  ① 온라인 스토어 주문/배송 데이터 수집 및 통합 관리
  ② 판매채널별 매출, 순이익, 수수료 통계 리포트 제공
  ③ 상품 키워드 등록 및 노출순위 추적 기능
  ④ 출석체크 및 포인트 적립, 충성고객 유도 시스템
  ⑤ 자사몰 연동, 커스터마이징 기능 등
  ⑥ 마케팅 도구 기능(리뷰 이벤트, 포인트 적립 등)의 제공
2. 회사는 운영상, 기술상 필요에 따라 서비스의 내용을 변경할 수 있습니다.

제5조 (서비스 이용시간 및 중단)
1. 서비스 이용은 연중무휴 1일 24시간을 원칙으로 합니다.
2. 다음의 경우 서비스 제공이 일시 중단될 수 있습니다.
  ① 시스템 정기점검, 보수, 교체 시
  ② 천재지변, 국가비상사태, 기술적 장애 등 불가항력의 경우
3. 회사는 서비스 중단 시 사전 또는 사후에 회원에게 공지합니다.

제6조 (회원가입 및 정보관리)
1. 회원은 아이디, 비밀번호, 닉네임, 전화번호 등 필수정보를 기재하여 가입합니다.
2. 회원은 기재한 정보에 변경사항이 발생할 경우 즉시 수정해야 합니다.
3. 회사는 회원이 허위 정보를 기재한 경우 서비스 제공을 중단하거나 회원 자격을 제한할 수 있습니다.

제7조 (회원의 의무)
1. 회원은 본 약관 및 관계 법령을 준수해야 하며, 다음 행위를 해서는 안 됩니다.
  ① 타인의 정보 도용
  ② 서비스의 장애를 초래하는 행위
  ③ 회사의 사전 동의 없는 상업적 이용
2. 회원은 보안 유지 및 계정 관리에 책임을 지며, 제3자에게 계정을 양도하거나 공유할 수 없습니다.

제8조 (회사의 의무)
1. 회사는 관련 법령과 본 약관이 정하는 바에 따라 지속적이고 안정적으로 서비스를 제공할 의무가 있습니다.
2. 회원의 정보를 보호하고, 정보 유출, 도용, 무단 접근을 방지하기 위한 보안 시스템을 운영합니다.

제9조 (지적재산권)
1. 서비스에 포함된 모든 콘텐츠, 소스코드, 디자인 등은 회사 또는 제휴사의 저작물로 보호됩니다.
2. 회원은 이를 무단 복제, 유포, 상업적 이용할 수 없습니다.

제10조 (책임 제한)
1. 회사는 천재지변, 불가항력적 사유로 서비스를 제공하지 못한 경우 책임을 지지 않습니다.
2. 회원의 귀책사유로 발생한 손해에 대해 회사는 책임을 지지 않습니다.

제11조 (분쟁 해결)
1. 본 약관 및 서비스 이용과 관련하여 분쟁이 발생할 경우, 회사와 회원은 성실히 협의하여 해결합니다.
2. 협의가 이루어지지 않을 경우, 민사소송법상의 관할 법원에 제소할 수 있습니다.

부칙
본 약관은 2025년 5월 1일부터 시행됩니다.
</pre>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="privacyModalLabel">개인정보처리방침</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="닫기"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- 여기에 전체 개인정보처리방침 내용을 복사하여 붙여넣으세요 -->
                                    <pre class="text-wrap" style="white-space: pre-wrap;">[개인정보처리방침]

[서비스명] (이하 "회사")는 「개인정보 보호법」 등 관련 법령에 따라 이용자의 개인정보를 보호하고 이와 관련한 고충을 신속하고 원활하게 처리할 수 있도록 다음과 같은 방침을 수립·공개합니다.

제1조 (개인정보의 수집 항목 및 방법)
1. 수집 항목
  ① 필수항목: 아이디, 비밀번호, 닉네임, 전화번호
  ② 서비스 이용 과정에서 IP 주소, 쿠키, 서비스 이용 기록, 접속 로그, 접속 시간 등 자동으로 생성되는 정보
2. 수집 방법
  ① 홈페이지 회원가입, 서비스 이용 과정에서 수집
  ② 고객센터 문의 시 정보 수집

제2조 (개인정보의 수집 및 이용 목적)
회사는 수집한 개인정보를 다음의 목적을 위해 사용합니다.
  ① 회원 식별 및 가입의사 확인
  ② 서비스 제공 및 콘텐츠 이용 통계 분석
  ③ 고객상담, 공지사항 전달, 분쟁 조정을 위한 기록보존
  ④ 출석체크 및 포인트 적립 시스템 운영
  ⑤ 불법 이용 방지 및 부정행위 탐지

제3조 (개인정보의 보유 및 이용기간)
회사는 원칙적으로 개인정보 수집 및 이용 목적이 달성된 후에는 해당 정보를 지체 없이 파기합니다. 단, 다음의 정보는 아래의 이유로 명시한 기간 동안 보존합니다.
  ① 회원 탈퇴 시 보존 정보: 아이디, 접속 이력
    - 보존 이유: 부정 이용 방지, 민원 처리
    - 보존 기간: 1년
  ② 전자상거래 관련 보존 정보
    - 계약 또는 청약철회 등에 관한 기록: 5년
    - 대금결제 및 재화 등의 공급에 관한 기록: 5년
    - 소비자 불만 또는 분쟁처리에 관한 기록: 3년

제4조 (개인정보의 제3자 제공)
회사는 원칙적으로 이용자의 개인정보를 외부에 제공하지 않습니다. 단, 다음의 경우는 예외로 합니다.
  ① 이용자가 사전에 동의한 경우
  ② 법령에 근거해 제공이 불가피한 경우
  ③ 서비스 운영을 위해 외부 위탁이 필요한 경우 (예: 클라우드 서버)

제5조 (개인정보처리의 위탁)
회사는 원활한 서비스 제공을 위해 다음과 같이 개인정보를 위탁하고 있습니다.
  - 수탁업체: [AWS 또는 웹호스팅 업체명]
  - 위탁업무: 시스템 운영, 데이터 보관
  - 보유 및 이용기간: 위탁 계약 종료 시까지

제6조 (이용자와 법정대리인의 권리·의무 및 행사방법)
1. 이용자는 언제든지 개인정보 열람, 정정, 삭제, 처리정지를 요청할 수 있습니다.
2. 법정대리인은 만 14세 미만 아동의 개인정보에 대해 열람, 정정, 삭제를 요청할 수 있습니다.
3. 권리행사는 이메일 또는 서면을 통해 가능합니다.

제7조 (개인정보의 파기 절차 및 방법)
1. 파기 절차: 목적 달성 후 별도 보관 또는 즉시 파기
2. 파기 방법: 전자적 파일은 복구불가능한 기술적 방법으로 삭제, 종이 문서는 분쇄 또는 소각

제8조 (개인정보의 안전성 확보 조치)
회사는 개인정보 보호를 위해 다음과 같은 기술적·관리적 조치를 취하고 있습니다.
  ① 개인정보에 대한 접근 제한
  ② 비밀번호 및 중요정보의 암호화 저장
  ③ 백신 프로그램 운영 및 주기적 점검
  ④ 개인정보 처리자에 대한 교육 실시

제9조 (개인정보 보호책임자)
- 책임자: [운영자 이름 또는 직책]
- 연락처: [이메일 또는 전화번호]

제10조 (개인정보처리방침의 변경)
이 방침은 시행일로부터 적용되며, 변경 내용은 웹사이트 공지사항을 통해 고지합니다.

부칙
본 개인정보처리방침은 2025년 5월 1일부터 시행됩니다.
</pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    let idCheckTimeout;
    let nicknameCheckTimeout;
    let isIdChecked = false;
    let isNickChecked = false;
    let isContactChecked = false;
    let isTermsChecked = false;

    $(document).ready(function () {
    // 전체 동의 체크 시 하위 항목 체크/해제
        $('#totalTermYn').on('change', function () {
            $('.termChk').prop('checked', this.checked);
        });

        // 개별 체크박스 변경 시 전체 동의 상태 변경
        $('.termChk').on('change', function () {
            const allChecked = $('.termChk').length === $('.termChk:checked').length;
            $('#totalTermYn').prop('checked', allChecked);
        });
    });

    $('#user_id').on('input', function () {
        clearTimeout(idCheckTimeout);
        const userId = $(this).val().trim();

        if (userId.length < 2) {
            $('#id_error').text('아이디는 최소 2자 이상 입력하세요.');
            isIdChecked = false;
            return;
        }

        idCheckTimeout = setTimeout(() => {
            $.ajax({
                url: './api/signup_api.php', // 데이터를 처리할 서버 URL
                type: 'POST',
                dataType: 'json',
                data: {user_id: userId, type :'idCheck'},
                success: function(response) { 
                    if(response.status=='exist'){
                        $('#id_error').text('이미 사용 중인 아이디입니다.');
                        $('#id_error').removeClass('plus');
                        isIdChecked = false;
                    }else if(response.status == "none"){
                        $('#id_error').addClass('plus');
                        $('#id_error').text('사용 가능한 아이디입니다.');
                        isIdChecked = true;
                    }        
                },
                error: function(xhr, status, error) {                  
                    // alert("관리자에게 문의해주세요.");
                    console.log(error);
                }
            });
        }, 300); // 0.3초 지연 후 요청
    });

    $('#nickname').on('input', function () {
        clearTimeout(nicknameCheckTimeout);
        const nickname = $(this).val().trim();

        if (nickname.length < 2) {
            $('#nick_error').text('닉네임은 최소 2자 이상 입력하세요.');
            isNickChecked = false;
            return;
        }

        nicknameCheckTimeout = setTimeout(() => {
            $.ajax({
                url: './api/signup_api.php', // 데이터를 처리할 서버 URL
                type: 'POST',
                dataType: 'json',
                data: {nickname: nickname, type :'nickCheck'},
                success: function(response) { 
                    if(response.status=='exist'){
                        $('#nick_error').removeClass('plus');
                        $('#nick_error').text('이미 사용 중인 닉네임입니다.');
                        isNickChecked = false;
                    }else if(response.status == "none"){
                        $('#nick_error').addClass('plus');
                        $('#nick_error').text('사용 가능한 닉네임입니다.');
                        isNickChecked = true;
                    }        
                },
                error: function(xhr, status, error) {                  
                    // alert("관리자에게 문의해주세요.");
                    console.log(error);
                }
            });
        }, 300); // 0.3초 지연 후 요청
    });


    $('#sendCodeBtn').on('click', function () {
        const phone = $('#contact').val().replace(/-/g, '');

        if (!isValidPhone(phone)) {
            $('#phoneMsg').text('올바른 휴대폰 번호를 입력하세요.');
            return;
        }

        $.ajax({
            url: './api/signup_api.php',
            type: 'POST',
            data: { type:'sendCode',phone: phone },
            success: function (response) {
                const res = JSON.parse(response);
                if (res.success) {
                    $('#verificationArea').show();
                    $('#phoneMsg').text('인증번호가 발송되었습니다.');
                } else {
                    $('#phoneMsg').text(res.message || '인증번호 전송 실패');
                }
            }
        });
    });

    $('#checkCodeBtn').on('click', function () {
        const phone = $('#contact').val().replace(/-/g, '');
        const code = $('#verifyCode').val();

        $.ajax({
            url: './api/signup_api.php',
            type: 'POST',
            data: { 'type':'checkCode', phone: phone, code: code },
            success: function (response) {
                const res = JSON.parse(response);
                if (res.success) {
                    $('#phoneMsg').addClass('plus');
                    $('#phoneMsg').text('인증 완료!');
                    isContactChecked = true;
                } else {
                    $('#phoneMsg').text('인증 실패. 코드를 다시 확인하세요.');
                }
            }
        });
    });

    
    $('#registerForm').on('submit', function (e) {
        e.preventDefault();
        const password = $('#password').val();
        const confirmPassword = $('#passwordChecker').val();
        const agreed = $('#totalTermYn').is(':checked');

        $('#pw_error, #agree_error').text('');

        if (password !== confirmPassword) {
            $('#pw_error').text('비밀번호가 일치하지 않습니다.');
            return;
        }

        if (!isIdChecked || !isNickChecked) {
            alert('아이디와 닉네임 중복 확인을 완료해주세요.');
            return;
        }

        if(!isContactChecked){
            alert('휴대폰 인증을 완료해주세요.');
            return;
        }

        if (!agreed) {
            alert('약관에 동의하셔야 가입이 가능합니다.');
            return;
        }

        $.ajax({
            url: './api/signup_api.php', // 데이터를 처리할 서버 URL
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            success: function(response) { 
                if(response.status){
                    alert('회원가입이 완료되었습니다.');
                    location.href = './login.php';
                }else if(!response.status){
                    alert(response.msg);
                }        
            },
            error: function(xhr, status, error) {                  
                // alert("관리자에게 문의해주세요.");
                console.log(error);
            }
        });
    });
</script>

</html>