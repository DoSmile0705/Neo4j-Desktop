/**
 * 管理画面 会社一覧 JS
 */

// 登録・編集フォーム モーダル コンポーネント

/**
 * メインブロック
 */
Vue.component('company-form-modal', {
    template: '#templateCompanyForm'
  , delimiters: ['(%', '%)']
  , props: [
    'state' // { show: true|false, eventOnChoiceProduct: null|event-name }
  ]
  , data: function() {
    return {
        saveUrl: null
      , messageState: {}
      , nowLoading: false
      , item: {}
    };
  }
  , computed: {
    caption: function() {
      const caption = '会社編集';
      return caption;
    }
  }

  , watch : {
  }

  , mounted: function() {
    this.$nextTick(function (){
      const self = this;
      const modal = $(self.$el);

      self.messageState = new PartsGlobalMessageState();
      self.saveUrl = $(self.$el).data('saveUrl');

      // イベント登録
      self.$watch('state.show', function(newValue) {
        if (newValue && modal.is(':hidden')) {
          modal.modal('show');
        } else if (!newValue && !modal.is(':hidden')) {
          modal.modal('hide');
        }
      });
      self.$watch('state.currentItem', function(newValue) {
        self.item = $.extend(true, { id: 'new' }, newValue);
      });

      // -- open前
      modal.on('show.bs.modal', function(e) {
        self.messageState.clear();
      });
      // -- open後
      modal.on('shown.bs.modal', function(e) {
        if (!self.state.show) {
          self.state.show = true;
        }
      });

      // -- close後
      modal.on('hidden.bs.modal', function(e) {
        if (self.state.show) {
          self.hideModal(); // 外部から閉じられた時の手当
        }
      })
    });
  }
  , methods: {
    hideModal: function() {
      this.state.show = false;
      this.reset();
    }

    , save: function() {
      const self = this;

      self.messageState.clear();
      self.nowLoading = true;

      const data = {
        item: self.item
      };

      $.ajax({
          type: "POST"
        , url: self.saveUrl
        , dataType: "json"
        , data: data
      })
        .done(function(result) {

          if (result.status === 'ok') {
            self.messageState.setMessage(result.message, 'alert-success');
            if (result.item) {
              self.$emit('update-item', result.item);
              self.item.id = Number(result.item.id); // new の場合のため、IDだけ補完しておく（他はフォームに残っている）
            }

          } else {
            const message = result.message.length > 0 ? result.message : '更新できませんでした。';
            self.messageState.setMessage(message, 'alert alert-danger');
          }
        })
        .fail(function(stat) {
          console.log(stat);
          self.messageState.setMessage('エラーが発生しました。', 'alert alert-danger');

        })
        . always(function() {
          self.nowLoading = false;
        });
    }

    , reset: function() {
      this.item = {};
      this.state.currentItem = null;
    }

    /**
     * 親イベント実行
     */
    , emitParentEvent: function(event, item) {
      this.$emit(event, item);
    }
  }
});



// 一覧画面 一覧テーブル 行コンポーネント
const vmComponentCompanyListItem = {
    template: '#templateCompanyListTableRow'
  , props: [
     'item'
  ]
  , data: function() {
    return {
    };
  }
  , computed: {
      displayCreated: function() {
      return this.item.created ? $.Plusnao.Date.getDateString(this.item.created, true) : '';
    }
    , displayUpdated: function() {
      return this.item.updated ? $.Plusnao.Date.getDateString(this.item.updated, true) : '';
    }
    , displayStatus: function() {
      return this.item.status === 0 ? '有効' : '無効';
    }
    , displayCss: function(key, target) {
      return this.item.status === 0 ? '' : 'shadow';
    }

  }
  , methods: {
    showEditForm: function() {
      this.$emit('show-edit-form', this.item);
    }

    , remove: function() {
      this.$emit('remove-item', this.item);
    }

  }
};


// 一覧画面 一覧表
const vmCompanyList = new Vue({
    el: '#companyList'
  , delimiters: ['(%', '%)']
  , data: {
      list: [] // データ
    , removeUrl: null

    , pageItemNum: 50
    , pageItemNumList: [ 20, 50, 100 ]
    , page: 1

    , messageState: {}
    , modalState: {
        message: ''
      , messageCssClass: ''
      , currentItem: {}
      , show: false
    }
  }
  , components: {
      'result-item': vmComponentCompanyListItem // 一覧テーブル
  }

  , mounted: function() {
    this.$nextTick(function () {
      // メッセージオブジェクト
      this.messageState = new PartsGlobalMessageState();
      this.list = [];
      this.removeUrl = $(this.$el).data('removeUrl');

      if (COMPANY_LIST_DATA) {
        for (let i = 0; i < COMPANY_LIST_DATA.length; i++) {
          let item = COMPANY_LIST_DATA[i];
          let row = this.convertItem(item);

          this.list.push(row);
        }
      } else {
        this.messageState.setMessage('データがありません。', 'alert-info');
      }
    });
  }

  , computed: {

    totalItemNum: function() {
      return this.listData.length;
    }

    // sort, filter済みデータ
    , listData: function() {
      const self = this;
      const list = self.list.slice(); // 破壊防止

      return list;
    }

    , pageData: function() {
      const startPage = (this.page - 1) * this.pageItemNum;
      return this.listData.slice(startPage, startPage + this.pageItemNum);
    }
  }
  , methods: {

      showPage: function(pageInfo) {
      this.page = pageInfo.page;
      this.pageItemNum = pageInfo.pageItemNum;
    }

    , showFormModal: function (item) {
      if (!item) { // 新規作成時
        item = {status : 0};
      }

      this.modalState.currentItem = item;
      this.modalState.show = true;
    }

    // 更新 or 新規追加
    , updateItem: function (item) {
      const row = this.convertItem(item);

      for (let i = 0; i < this.list.length; i++) {
        let compare = this.list[i];
        if (compare.id == item.id) {
          this.list.splice(i, 1, row); // 更新トリガのためにspliceでないとダメ
          return;
        }
      }

      // 一致するitemが無かった。=> 新規追加
      this.list.push(row);
    }

    // 削除
    , removeItem: function (item) {
      const self = this;

      if (!confirm('この会社を削除してよろしいですか？')) {
        return;
      }

      self.messageState.clear();
      self.nowLoading = true;

      const data = {
        id: item.id
      };

      $.ajax({
          type: "POST"
        , url: self.removeUrl
        , dataType: "json"
        , data: data
      })
        .done(function(result) {

          if (result.status === 'ok') {
            self.messageState.setMessage(result.message, 'alert-success');

            if (result.id) {
              for (let i = 0; i < self.list.length; i++) {
                if (self.list[i].id == result.id) {
                  self.list.splice(i, 1);
                  break;
                }
              }
            }

          } else {
            const message = result.message.length > 0 ? result.message : '更新できませんでした。';
            self.messageState.setMessage(message, 'alert alert-danger');
          }
        })
        .fail(function(stat) {
          self.messageState.setMessage('エラーが発生しました。', 'alert alert-danger');

        })
        . always(function() {
          self.nowLoading = false;
        });

    }

    // 取得データをJS用に変換
    , convertItem: function(item) {
       return {
          id      : Number(item.id)
        , code    : item.code
        , name    : item.name
        , displayOrder    : Number(item.display_order)
        , status    : Number(item.status)
        , created : (item.created ? new Date(item.created.replace(/-/g, "/")) : null) // replace for firefox, IE
        , updated : (item.updated ? new Date(item.updated.replace(/-/g, "/")) : null) // replace for firefox, IE
      };
    }




    // ----------------------------------
    // イベントハンドラ
    // ----------------------------------


  }

});

