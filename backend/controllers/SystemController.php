<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

namespace backend\controllers;

use backend\models\FormUploadTrans;
use common\components\Config;
use common\components\SystemLog;
use common\models\Login;
use common\models\Post;
use common\models\SystemDictionary;
use common\models\SystemMessage;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\BaseFileHelper;
use yii\mongodb\Exception;

class SystemController extends BackendController
{

    public $activeMenu = 'system';

    /**
     * @return string|void|\yii\web\Response
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
                        $this->addSuccess(__('File {file} has removed', ['file' => $name]));
                    }
                } else {
                    $this->addError(__('You cannot delete backups after a week'));
                }
                return $this->redirect('backup');
            }
        }
        $data = [];
        foreach (glob($dir . '*.bak.*') as $file) {
            $data[] = [
                'name' => basename($file),
                'size' => Yii::$app->formatter->asSize(filesize($file)),
                'time' => Yii::$app->formatter->asDatetime(filemtime($file)),
                'date' => intval(filemtime($file)),
            ];
        }

        $provider = new ArrayDataProvider([
                                              'allModels'  => $data,
                                              'sort'       => [
                                                  'attributes'   => ['name', 'size', 'time', 'date'],
                                                  'defaultOrder' => ['date' => SORT_DESC],
                                              ],
                                              'pagination' => [
                                                  'pageSize' => 20,
                                              ],
                                          ]);
        return $this->render('backup', ['dataProvider' => $provider]);
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
        $searchModel = new SystemMessage(['scenario' => 'search']);

        $model = new FormUploadTrans();
        if ($this->_user()->canAccessToResource('system/upload-trans')) {
            if ($model->load(Yii::$app->request->post())) {
                if ($data = $model->uploadData()) {
                    $this->addSuccess(__('{count} message updated successfully', ['count' => count($data)]));
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
            'searchModel'  => $searchModel,
            'model'        => $model,
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
        $result    = [
            array_merge(['category', 'message'], array_keys($languages)),
        ];

        foreach (SystemMessage::find()->orderBy(['_id' => SORT_ASC])->all() as $message) {
            $data = [
                'category' => $message->category,
                'message'  => $message->message,
            ];

            foreach ($languages as $lang => $label) {
                $data[$lang] = $message->hasAttribute($lang) ? $message->getAttribute($lang) : "";
            }

            $result[] = $data;
        }

        $fileName = Yii::getAlias('@runtime') . DS . DS . 'trans_' . time() . '.csv';
        if ($handle = fopen($fileName, 'w+')) {
            foreach ($result as $row)
                fputcsv($handle, $row, ",", '"');
            fclose($handle);

            Yii::$app->response->sendFile($fileName);
        }
    }


    /**
     * @param bool|string $id
     * @return SystemDictionary|array|string
     * @resource System | Manage Dictionary | tag/index
     */
    public function actionDictionary($id = false)
    {
        if ($id) {
            $model = SystemDictionary::findOne($id);
        } else {
            $model = new SystemDictionary();
        }

        $searchModel = new SystemDictionary();

        if ($this->get('save')) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                if ($id) {
                    $this->addSuccess(__('Dictionary {name} updated successfully', ['name' => $model->latin]));
                } else {
                    $this->addSuccess(__('Dictionary {name} created successfully', ['name' => $model->latin]));
                }

                if (!$this->isAjax())
                    return $this->redirect(['index', 'id' => $model->id]);
            }
        }
        if ($this->get('delete')) {
            try {
                $model->delete();
                $this->addSuccess(__('Dictionary {name} deleted successfully', ['name' => $model->latin]));
                return $this->redirect(['system/dictionary']);
            } catch (Exception $exception) {
                $exception->getMessage();
            }
        }
        return $this->render('dictionary', [
            'model'        => $model,
            'searchModel'  => $searchModel,
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

        $this->addSuccess(__('System cache cleared successfully'));
        return $this->redirect(Yii::$app->request->getReferrer() ?: ['dashboard/index']);
    }

    /**
     * @return string
     * @resource System | Login History | system/logins
     */
    public function actionLogins()
    {
        $searchModel = new Login(['scenario' => 'search']);

        return $this->render('logins', [
            'dataProvider' => $searchModel->search(Yii::$app->request->get()),
            'searchModel'  => $searchModel,
        ]);
    }

    /**
     * @return string
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
            'searchModel'  => $searchModel,
        ]);
    }

    /**
     * @return string
     * @resource System | User Logs | system/user-logs
     */
    public function actionUserLogs()
    {
        $searchModel = new SystemLog();

        return $this->render('user-logs', [
            'dataProvider' => $searchModel->search(Yii::$app->request->get()),
            'searchModel'  => $searchModel,
        ]);
    }


    /**
     * @return string
     * @resource System | User Logs | system/user-logs-view
     */
    public function actionUserLogsView($id)
    {
        $model = SystemLog::findOne($id);

        return $this->render('user-logs-view', [
            'model' => $model,
        ]);
    }
}
