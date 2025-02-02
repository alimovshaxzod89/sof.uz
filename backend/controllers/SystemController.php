<?php

namespace backend\controllers;

use backend\models\FormUploadTrans;
use common\components\Config;
use common\components\SystemLog;
use common\models\Login;
use common\models\Post;
use common\models\SystemDictionary;
use common\models\SystemMessage;
use Yii;
use yii\helpers\BaseFileHelper;
use yii\mongodb\Exception;

class SystemController extends BackendController
{

    public $activeMenu = 'system';

    /**
     * @return string|void|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @resource System | System Backups | system/backup
     */
    public function actionBackup()
    {
        $dir = Yii::getAlias('@backups') . DS;
        if ($name = $this->get('id')) {
            if (file_exists($dir . $name)) {
                return Yii::$app->response->sendFile($dir . $name);
            }
        }

        if ($name = $this->get('rem')) {
            if (file_exists($dir . $name)) {
                $time = time() - intval(filemtime($dir . $name));
                if ($time < 3600 * 24 * 7) {
                    if (unlink($dir . $name)) {
                        $this->addSuccess(__('File `{file}` has removed.', ['file' => $name]));
                    }
                } else {
                    $this->addError(__('You cannot delete backups after a week'));
                }
                return $this->redirect('backup');
            }
        }

        return $this->render('backup', [
            'dataProvider' => Config::getBackupProvider()
        ]);
    }

    /**
     * @return string
     * @resource System | System Logs | system/logs
     */
    public function actionLog()
    {
        $searchModel = new SystemLog();

        return $this->render('user-logs', [
            'searchModel' => $searchModel,
            'dataProvider' => $searchModel->search(Yii::$app->request->get()),
        ]);
    }

    /**
     * @return string
     * @resource System | System Logs | system/user-logs-view
     */
    public function actionUserLogsView($id)
    {
        $model = SystemLog::findOne($id);

        return $this->render('user-logs-view', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws \yii\db\StaleObjectException
     * @resource System | Manage Translations | system/deltrans
     */
    public function actionDelete($id)
    {
        if ($message = SystemMessage::findOne(['_id' => $id])) {
            if ($message->delete()) {
            }
        }
    }

    /**
     * @param $id
     * @return string
     * @resource System | Manage Translations | system/translate
     */
    public function actionTranslate($id)
    {
        /**
         * @var $message SystemMessage;
         */
        $message = SystemMessage::findOne(['_id' => $id]);

        if ($message->load(Yii::$app->request->post()) && $message->save()) {
            return null;
        }
        return $this->renderPartial('translate', ['model' => $message]);
    }

    /**
     * @param bool $convert
     * @return string
     * @resource System | Manage Translations | system/translation
     * @resource System | Upload Translations | system/upload-trans
     */
    public function actionTranslation($convert = false)
    {
        $searchModel = new SystemMessage(['scenario' => SystemMessage::SCENARIO_SEARCH]);

        $model = new FormUploadTrans();
        if ($this->_user()->canAccessToResource('system/upload-trans')) {
            if ($model->load(Yii::$app->request->post())) {
                if ($data = $model->uploadData()) {
                    $this->addSuccess(
                        __('Inserted: `{inserted}` and Updated: `{updated}` successfully', $data)
                    );
                } else {
                    $errors = $model->getFirstErrors();
                    $this->addError(array_pop($errors));
                }

                return $this->refresh();
            }
        }
        /**
         * @var SystemMessage $message
         */
        if ($convert && Config::isLatinCyrill() && !Yii::$app->request->isAjax) {
            $count = 0;
            foreach (SystemMessage::find()->all() as $message) {
                $count += $message->transliterateUzbek();
            }

            if ($count) {
                $this->addSuccess(__('{count} messages transliterated successfully', ['count' => $count]));
            }
            return $this->redirect(['system/translation']);
        }

        return $this->render('translation', [
            'dataProvider' => $searchModel->search(Yii::$app->request->get()),
            'searchModel' => $searchModel,
            'model' => $model,
        ]);
    }


    /**
     * @return string
     * @resource System | Download Translations | system/download
     */
    public function actionDownload()
    {
        /**
         * @var $message SystemMessage
         */
        $languages = Config::getLanguageOptions();
        $result = [
            array_merge(['category', 'message'], array_keys($languages)),
        ];

        foreach (SystemMessage::find()->orderBy(['_id' => SORT_ASC])->all() as $message) {
            $data = [
                'category' => $message->category,
                'message' => $message->message,
            ];

            foreach ($languages as $lang => $label) {
                $data[$lang] = $message->hasAttribute($lang) ? $message->getAttribute($lang) : "";
            }

            $result[] = $data;
        }

        $fileName = Yii::getAlias('@runtime') . DS . 'trans_' . time() . '.csv';
        if ($handle = fopen($fileName, 'w+')) {
            foreach ($result as $row)
                fputcsv($handle, $row, ",", '"');
            fclose($handle);

            Yii::$app->response->sendFile($fileName);
        }
    }


    /**
     * @param mixed $id
     * @return SystemDictionary|array|string
     * @throws \yii\db\StaleObjectException
     * @resource System | Manage Dictionary | system/dictionary
     */
    public function actionDictionary($id = false)
    {
        $searchModel = new SystemDictionary();
        $model = $id ? SystemDictionary::findOne($id) : new SystemDictionary();

        if ($this->get('save')) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $this->addSuccess(
                    __('Dictionary {name} updated successfully', [
                        'name' => $model->latin,
                        'action' => __($id ? 'updated' : 'created')
                    ])
                );

                if (!$this->isAjax())
                    return $this->redirect(['index', 'id' => $model->getId()]);
            }
        }

        if ($this->get('delete')) {
            try {
                $model->delete();
                $this->addSuccess(
                    __('Dictionary `{name}` deleted successfully', [
                        'name' => $model->latin
                    ])
                );
                return $this->redirect(['system/dictionary']);
            } catch (Exception $exception) {
                $exception->getMessage();
            }
        }

        return $this->render('dictionary', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $searchModel->search(Yii::$app->request->get()),
        ]);
    }


    /**
     * @return string|\yii\web\Response
     * @resource System | Change Configuration | system/configuration
     */
    public function actionConfiguration()
    {
        if (Yii::$app->request->getIsPost() && Config::batchUpdate($this->getPost('config'))) {
            $this->addSuccess(__('Configuration updated successfully'));

            return $this->refresh();
        }

        return $this->render('configuration', []);
    }

    /**
     * @return string|\yii\web\Response
     * @resource System | Create DB Snapshot | system/snapshot
     */
    public function actionSnapshot()
    {
        $old_path = getcwd();
        chdir(Yii::getAlias('@backups'));

        putenv("PATH=/home/user/bin/:" . $_SERVER["PATH"] . "");
        $output = shell_exec('./backup.sh no');

        chdir($old_path);


        return $this->redirect(['system/backup']);
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\base\ErrorException
     */
    public function actionCache()
    {
        $dirs = [
            '@frontend/runtime/cache',
            '@api/runtime/cache',
            '@backend/runtime/cache',
        ];
        foreach ($dirs as $dir) {
            $dir = Yii::getAlias($dir);
            if (is_dir($dir))
                BaseFileHelper::removeDirectory($dir);
        }

        Yii::$app->cache->flush();
        file_get_contents('https://sof.uz/uz/site/dom');
        $this->addSuccess(__('System cache cleared successfully'));
        return $this->redirect(Yii::$app->request->getReferrer() ?: ['dashboard/index']);
    }

    /**
     * @return string
     * @resource System | Login History | system/login
     */
    public function actionLogin()
    {
        $searchModel = new Login(['scenario' => 'search']);

        return $this->render('logins', [
            'dataProvider' => $searchModel->search(Yii::$app->request->get()),
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws \yii\db\StaleObjectException
     * @resource System | Login History | system/del-login
     */
    public function actionDelLogin($id)
    {
        if ($message = Login::findOne($id)) {
            if ($message->delete()) {
            }
        }

        return $this->actionLogins();
    }


    /**
     * @return string
     * @resource System | View Trash | system/trash
     */
    public function actionTrash()
    {
        $searchModel = new Post(['scenario' => 'search']);

        return $this->render('trash', [
            'dataProvider' => $searchModel->searchTrash(Yii::$app->request->get()),
            'searchModel' => $searchModel,
        ]);
    }

}
