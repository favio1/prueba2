<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use Da\QrCode\QrCode;
use yii\web\UploadedFile;
use yii\base\DynamicModel;
use yii\helpers\Url;
use Zxing\QrReader;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $request = \Yii::$app->request;
        $qr = null;
        $rutaGuardado = Yii::getAlias('@app/web').'/qrs/'.'tempImsssagen.png';
        $model = DynamicModel::validateData(['pdf']);
        $model->addRule(['pdf'], 'required')
            ->validate();
        if ( $request->isPost && $model->load($request->post())) {
            // saveing file
            $image = UploadedFile::getInstance($model, 'pdf');
            if ($image){
                $imageName = 'pdf'.date('ymdHis').'.'.$image->getExtension();
                $image->saveAs(Yii::getAlias('@app/web').'/uploads/'.$imageName);
                $linkFile = $imageName;
            }
            // makeing qr
            $qrCode = (new QrCode(Url::base(true).'/uploads/'.$linkFile))
                ->setSize(250)
                ->setMargin(5);
            $qrCode->writeFile($rutaGuardado);
            header('Content-Type: '.$qrCode->getContentType());
            $qr = $qrCode->writeDataUri();
            Yii::$app->session->setFlash('success', ['Coigo QR Generado']);
        }
        return $this->render('index', [
            'model'=>$model,
            'qr'=>$qr,
            'rutaQr'=>$rutaGuardado
        ]);
    }

    public function actionQr() {
        $request = \Yii::$app->request;
        $model = DynamicModel::validateData(['qr', 'required']);
        $model->addRule(['qr'], 'required')
            ->validate();
        $path = Yii::getAlias('@app/web').'/qrs/';
        if ( $request->isPost && $model->load($request->post())) {
            try {
                // saveing file
                $image = UploadedFile::getInstance($model, 'qr');
                if ($image){
                    $imageName = 'pdf'.date('ymdHis').'.'.$image->getExtension();
                    $image->saveAs($path.$imageName);
                }
                
                $qrcode = new QrReader($path.$imageName);

                if (!$qrcode->text()) {
                    throw new \Exception("La imagen no es un codigo QR", 1);
                }
                return $this->redirect(['showpdf', 'pdf'=>$qrcode->text()]);
            } catch (\Throwable $th) {
                \Yii::$app->session->setFlash('warning', $th->getMessage());
            }
        }
        return $this->render('qr', [
            'model'=>$model,
        ]);
    }

    public function actionDescargar() {
        $qr = $_POST['link'];
        $res = Yii::$app->response->sendFile($qr, 'imagen_qr'.date('Ymdhis').'.png');
        return true;
        Yii::$app->session->setFlash('success', 'Se descargo correctamente');
        // sleep(5); 
        return $this->redirect(['/']);
    }

    public function actionShowpdf($pdf) {
        return $this->render('showpdf', [
            'pdf'=>$pdf
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
